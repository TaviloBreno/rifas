<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RaffleModel;
use App\Models\RaffleNumberModel;
use App\Models\OrderModel;
use App\Models\WinnerModel;
use App\Models\SettingModel;
use App\Entities\Order;
use App\Libraries\PixGenerator;

class HomeController extends BaseController
{
    protected RaffleModel $raffleModel;
    protected WinnerModel $winnerModel;
    protected SettingModel $settingModel;

    public function __construct()
    {
        $this->raffleModel = new RaffleModel();
        $this->winnerModel = new WinnerModel();
        $this->settingModel = new SettingModel();
    }

    /**
     * Página inicial - lista rifas ativas
     */
    public function index()
    {
        $data = [
            'featuredRaffles' => $this->raffleModel->getFeatured(3),
            'raffles'         => $this->raffleModel->getActive(),
            'recentWinners'   => $this->winnerModel->getRecent(5),
            'settings'        => $this->settingModel->getAllAsArray(),
        ];

        return view('public/home', $data);
    }

    /**
     * Exibe detalhes de uma rifa
     */
    public function raffle($slug = null)
    {
        $raffle = $this->raffleModel->findBySlug($slug);
        
        if (!$raffle) {
            $raffle = $this->raffleModel->find($slug);
        }

        if (!$raffle) {
            return redirect()->to('/')->with('error', 'Rifa não encontrada.');
        }

        $numberModel = new RaffleNumberModel();
        
        $data = [
            'raffle'   => $raffle,
            'numbers'  => $numberModel->getAllByRaffle($raffle->id),
            'stats'    => $numberModel->getStats($raffle->id),
            'settings' => $this->settingModel->getAllAsArray(),
        ];

        // Se a rifa já foi sorteada, buscar ganhador
        if ($raffle->isFinished()) {
            $data['winners'] = $this->winnerModel->getByRaffle($raffle->id);
        }

        return view('public/raffle_detail', $data);
    }

    /**
     * Página de checkout - seleção de números
     */
    public function checkout($slug = null)
    {
        $raffle = $this->raffleModel->findBySlug($slug);
        
        if (!$raffle) {
            $raffle = $this->raffleModel->find($slug);
        }

        if (!$raffle || !$raffle->isActive()) {
            return redirect()->to('/')->with('error', 'Rifa não disponível para compra.');
        }

        $numberModel = new RaffleNumberModel();
        
        // Libera números com reserva expirada
        $numberModel->releaseExpiredReservations();

        $data = [
            'raffle'   => $raffle,
            'numbers'  => $numberModel->getAllByRaffle($raffle->id),
            'stats'    => $numberModel->getStats($raffle->id),
            'settings' => $this->settingModel->getAllAsArray(),
        ];

        return view('public/checkout', $data);
    }

    /**
     * Processa a compra de números
     */
    public function processCheckout()
    {
        $raffleId = $this->request->getPost('raffle_id');
        $selectedNumbers = $this->request->getPost('numbers');
        
        $raffle = $this->raffleModel->find($raffleId);
        
        if (!$raffle || !$raffle->isActive()) {
            return redirect()->back()->with('error', 'Rifa não disponível para compra.');
        }

        if (empty($selectedNumbers) || !is_array($selectedNumbers)) {
            return redirect()->back()->with('error', 'Selecione pelo menos um número.');
        }

        // Valida dados do cliente
        $rules = [
            'customer_name'  => 'required|min_length[3]|max_length[255]',
            'customer_email' => 'required|valid_email',
            'customer_phone' => 'required|min_length[10]|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $numberModel = new RaffleNumberModel();
        $orderModel = new OrderModel();

        // Verifica se os números estão disponíveis
        $numbers = $numberModel->getByNumbers($raffleId, $selectedNumbers);
        $availableNumbers = [];

        foreach ($numbers as $number) {
            if ($number->isAvailable()) {
                $availableNumbers[] = $number;
            }
        }

        if (count($availableNumbers) !== count($selectedNumbers)) {
            return redirect()->back()->with('error', 'Alguns números selecionados não estão mais disponíveis.');
        }

        // Valida quantidade mínima e máxima
        $count = count($selectedNumbers);
        if ($count < $raffle->min_per_purchase) {
            return redirect()->back()->with('error', "Selecione pelo menos {$raffle->min_per_purchase} número(s).");
        }

        if ($count > $raffle->max_per_purchase) {
            return redirect()->back()->with('error', "Selecione no máximo {$raffle->max_per_purchase} números.");
        }

        // Calcula total
        $totalAmount = $raffle->price * $count;

        // Tempo de expiração
        $expirationMinutes = $this->settingModel->get('payment_expiration_minutes', 30);
        $expiresAt = date('Y-m-d H:i:s', strtotime("+{$expirationMinutes} minutes"));

        // Cria o pedido
        $order = new Order([
            'user_id'        => session()->get('user_id'),
            'raffle_id'      => $raffleId,
            'customer_name'  => $this->request->getPost('customer_name'),
            'customer_email' => $this->request->getPost('customer_email'),
            'customer_phone' => preg_replace('/\D/', '', $this->request->getPost('customer_phone')),
            'customer_cpf'   => preg_replace('/\D/', '', $this->request->getPost('customer_cpf') ?? ''),
            'total_numbers'  => $count,
            'total_amount'   => $totalAmount,
            'status'         => 'pending',
            'payment_method' => 'pix',
            'expires_at'     => $expiresAt,
        ]);

        $orderId = $orderModel->insert($order);

        if (!$orderId) {
            return redirect()->back()->with('error', 'Erro ao criar pedido. Tente novamente.');
        }

        // Reserva os números
        $numberIds = array_column($availableNumbers, 'id');
        $numberModel->reserveNumbers($numberIds, $orderId, session()->get('user_id'), $expirationMinutes);

        return redirect()->to("/pagamento/{$orderId}");
    }

    /**
     * Página de pagamento
     */
    public function payment($orderId = null)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->getWithRaffle($orderId);

        if (!$order) {
            return redirect()->to('/')->with('error', 'Pedido não encontrado.');
        }

        // Se já está pago, redireciona para confirmação
        if ($order->isPaid()) {
            return redirect()->to("/pedido/{$orderId}");
        }

        // Se expirou, cancela
        if ($order->isExpired() && $order->status === 'pending') {
            $orderModel->markAsExpired($orderId);
            
            $numberModel = new RaffleNumberModel();
            $numberModel->releaseNumbers($orderId);
            
            return redirect()->to('/')->with('error', 'Seu pedido expirou. Os números foram liberados.');
        }

        $numberModel = new RaffleNumberModel();
        $numbers = $numberModel->getByOrder($orderId);

        // Gera código PIX
        $pixGenerator = PixGenerator::fromSettings();
        $pixGenerator->setAmount($order->total_amount);
        $pixGenerator->setTxId('RIFA' . str_pad($orderId, 6, '0', STR_PAD_LEFT));
        $pixGenerator->setDescription('Rifa ' . substr($order->raffle_name, 0, 15));

        $raffle = $this->raffleModel->find($order->raffle_id);

        $data = [
            'order'    => $order,
            'raffle'   => $raffle,
            'numbers'  => $numbers,
            'pixCode'  => $pixGenerator->generate(),
            'qrCodeUrl'=> $pixGenerator->getQRCodeUrl(250),
            'settings' => $this->settingModel->getAllAsArray(),
        ];

        return view('public/payment', $data);
    }

    /**
     * Verifica status do pedido (AJAX)
     */
    public function checkOrderStatus($orderId = null)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);

        if (!$order) {
            return $this->response->setJSON(['error' => 'Pedido não encontrado']);
        }

        return $this->response->setJSON([
            'status'        => $order->status,
            'status_label'  => $order->getStatusLabel(),
            'is_paid'       => $order->isPaid(),
            'is_expired'    => $order->isExpired(),
            'remaining_time'=> $order->getRemainingTime(),
        ]);
    }

    /**
     * Página de confirmação/detalhes do pedido
     */
    public function orderDetails($orderId = null)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->getWithRaffle($orderId);

        // Verifica se o pedido pertence ao usuário ou se é acesso público por email
        $canAccess = false;
        
        if (session()->get('user_id') && $order && $order->user_id == session()->get('user_id')) {
            $canAccess = true;
        }
        
        // Para acesso sem login, verifica se passou email na sessão
        if ($order && session()->get('order_email_' . $orderId) === $order->customer_email) {
            $canAccess = true;
        }

        if (!$order) {
            return redirect()->to('/')->with('error', 'Pedido não encontrado.');
        }

        $numberModel = new RaffleNumberModel();
        $raffle = $this->raffleModel->find($order->raffle_id);

        $data = [
            'order'    => $order,
            'raffle'   => $raffle,
            'numbers'  => $numberModel->getByOrder($orderId),
            'settings' => $this->settingModel->getAllAsArray(),
        ];

        return view('public/order_details', $data);
    }

    /**
     * Consulta pedidos por email
     */
    public function myOrders()
    {
        return view('public/my_orders', [
            'settings' => $this->settingModel->getAllAsArray(),
        ]);
    }

    /**
     * Busca pedidos por email
     */
    public function searchOrders()
    {
        $email = $this->request->getPost('email');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error', 'Digite um e-mail válido.');
        }

        $orderModel = new OrderModel();
        $orders = $orderModel->getByEmail($email);

        // Salva permissão de acesso para cada pedido
        foreach ($orders as $order) {
            session()->set('order_email_' . $order->id, $email);
        }

        return view('public/my_orders', [
            'orders'   => $orders,
            'email'    => $email,
            'settings' => $this->settingModel->getAllAsArray(),
        ]);
    }

    /**
     * Lista de ganhadores
     */
    public function winners()
    {
        $data = [
            'winners'  => $this->winnerModel->getRecent(50),
            'settings' => $this->settingModel->getAllAsArray(),
        ];

        return view('public/winners', $data);
    }

    /**
     * Página de contato
     */
    public function contact()
    {
        return view('public/contact', [
            'settings' => $this->settingModel->getAllAsArray(),
        ]);
    }
}
