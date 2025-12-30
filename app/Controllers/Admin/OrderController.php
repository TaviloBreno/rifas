<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\RaffleModel;
use App\Models\RaffleNumberModel;

class OrderController extends BaseController
{
    protected OrderModel $orderModel;
    protected RaffleModel $raffleModel;
    protected RaffleNumberModel $numberModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->raffleModel = new RaffleModel();
        $this->numberModel = new RaffleNumberModel();
    }

    /**
     * Lista todos os pedidos
     */
    public function index()
    {
        $filters = [
            'status'    => $this->request->getGet('status'),
            'raffle_id' => $this->request->getGet('raffle_id'),
            'search'    => $this->request->getGet('search'),
        ];

        $data = [
            'orders'  => $this->orderModel->getForAdmin($filters),
            'raffles' => $this->raffleModel->findAll(),
            'filters' => $filters,
        ];

        return view('admin/orders/index', $data);
    }

    /**
     * Exibe detalhes de um pedido
     */
    public function show($id = null)
    {
        $order = $this->orderModel->getWithRaffle($id);

        if (!$order) {
            return redirect()->to('admin/orders')->with('error', 'Pedido não encontrado.');
        }

        $data = [
            'order'   => $order,
            'raffle'  => $this->raffleModel->find($order->raffle_id),
            'numbers' => $this->numberModel->getByOrder($id),
        ];

        return view('admin/orders/show', $data);
    }

    /**
     * Confirma pagamento manualmente
     */
    public function confirmPayment($id = null)
    {
        $order = $this->orderModel->find($id);

        if (!$order) {
            return redirect()->to('admin/orders')->with('error', 'Pedido não encontrado.');
        }

        if ($order->status !== 'pending') {
            return redirect()->to('admin/orders/' . $id)->with('error', 'Este pedido não está pendente.');
        }

        // Marca como pago
        $this->orderModel->markAsPaid($id, 'pix_manual', 'MANUAL-' . date('YmdHis'));

        // Confirma venda dos números
        $this->numberModel->confirmSale($id);

        return redirect()->to('admin/orders/' . $id)->with('success', 'Pagamento confirmado com sucesso!');
    }

    /**
     * Cancela um pedido
     */
    public function cancel($id = null)
    {
        $order = $this->orderModel->find($id);

        if (!$order) {
            return redirect()->to('admin/orders')->with('error', 'Pedido não encontrado.');
        }

        if ($order->status === 'paid') {
            return redirect()->to('admin/orders/' . $id)->with('error', 'Não é possível cancelar um pedido pago.');
        }

        $notes = $this->request->getPost('notes') ?: 'Cancelado pelo administrador';
        
        // Cancela o pedido
        $this->orderModel->markAsCancelled($id, $notes);

        // Libera os números
        $this->numberModel->releaseNumbers($id);

        return redirect()->to('admin/orders/' . $id)->with('success', 'Pedido cancelado com sucesso!');
    }

    /**
     * Processa pedidos expirados
     */
    public function processExpired()
    {
        // Expira pedidos pendentes
        $expiredCount = $this->orderModel->expirePendingOrders();

        // Libera números reservados expirados
        $releasedCount = $this->numberModel->releaseExpiredReservations();

        return redirect()->to('admin/orders')->with('success', "Processado: {$expiredCount} pedidos expirados, {$releasedCount} números liberados.");
    }
}
