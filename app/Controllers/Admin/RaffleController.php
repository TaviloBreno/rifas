<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RaffleModel;
use App\Models\RaffleNumberModel;
use App\Models\WinnerModel;
use App\Entities\Raffle;

class RaffleController extends BaseController
{
    protected RaffleModel $raffleModel;
    protected RaffleNumberModel $numberModel;

    public function __construct()
    {
        $this->raffleModel = new RaffleModel();
        $this->numberModel = new RaffleNumberModel();
    }

    /**
     * Lista todas as rifas
     */
    public function index()
    {
        $data = [
            'raffles' => $this->raffleModel->orderBy('created_at', 'DESC')->findAll(),
        ];

        return view('admin/raffles/index', $data);
    }

    /**
     * Formulário de nova rifa
     */
    public function new()
    {
        return view('admin/raffles/form', [
            'raffle' => null,
        ]);
    }

    /**
     * Cria uma nova rifa
     */
    public function create()
    {
        $rules = [
            'name'              => 'required|min_length[3]|max_length[255]',
            'description'       => 'permit_empty',
            'prize_description' => 'permit_empty|max_length[500]',
            'price'             => 'required|decimal|greater_than[0]',
            'quantity'          => 'required|integer|greater_than[0]|less_than_equal_to[100000]',
            'start_number'      => 'permit_empty|integer|greater_than_equal_to[0]',
            'min_per_purchase'  => 'permit_empty|integer|greater_than[0]',
            'max_per_purchase'  => 'permit_empty|integer|greater_than[0]',
            'draw_date'         => 'permit_empty|valid_date',
            'image'             => 'permit_empty|uploaded[image]|max_size[image,2048]|is_image[image]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Processa upload de imagem
        $imageName = null;
        $image = $this->request->getFile('image');
        
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move(FCPATH . 'uploads', $imageName);
        }

        $raffle = new Raffle([
            'name'              => $this->request->getPost('name'),
            'description'       => $this->request->getPost('description'),
            'prize_description' => $this->request->getPost('prize_description'),
            'price'             => $this->request->getPost('price'),
            'quantity'          => $this->request->getPost('quantity'),
            'start_number'      => $this->request->getPost('start_number') ?: 0,
            'min_per_purchase'  => $this->request->getPost('min_per_purchase') ?: 1,
            'max_per_purchase'  => $this->request->getPost('max_per_purchase') ?: 100,
            'draw_date'         => $this->request->getPost('draw_date') ?: null,
            'image'             => $imageName,
            'status'            => 'active',
            'is_featured'       => $this->request->getPost('is_featured') ? 1 : 0,
            'user_id'           => session()->get('user_id'),
        ]);

        $raffleId = $this->raffleModel->insert($raffle);

        if (!$raffleId) {
            return redirect()->back()->withInput()->with('error', 'Erro ao criar rifa.');
        }

        // Gera os números automaticamente se marcado
        if ($this->request->getPost('generate_numbers')) {
            $this->numberModel->generateNumbers(
                $raffleId,
                $raffle->quantity,
                $raffle->start_number
            );
            
            $this->raffleModel->update($raffleId, ['numbers_generated' => 1]);
        }

        return redirect()->to('admin/raffles')->with('success', 'Rifa criada com sucesso!');
    }

    /**
     * Exibe detalhes de uma rifa
     */
    public function show($id = null)
    {
        $raffle = $this->raffleModel->find($id);

        if (!$raffle) {
            return redirect()->to('admin/raffles')->with('error', 'Rifa não encontrada.');
        }

        $data = [
            'raffle'  => $raffle,
            'numbers' => $this->numberModel->getAllByRaffle($id),
            'stats'   => $this->numberModel->getStats($id),
        ];

        return view('admin/raffles/show', $data);
    }

    /**
     * Formulário de edição
     */
    public function edit($id = null)
    {
        $raffle = $this->raffleModel->find($id);

        if (!$raffle) {
            return redirect()->to('admin/raffles')->with('error', 'Rifa não encontrada.');
        }

        return view('admin/raffles/form', [
            'raffle' => $raffle,
        ]);
    }

    /**
     * Atualiza uma rifa
     */
    public function update($id = null)
    {
        $raffle = $this->raffleModel->find($id);

        if (!$raffle) {
            return redirect()->to('admin/raffles')->with('error', 'Rifa não encontrada.');
        }

        $rules = [
            'name'              => 'required|min_length[3]|max_length[255]',
            'description'       => 'permit_empty',
            'prize_description' => 'permit_empty|max_length[500]',
            'price'             => 'required|decimal|greater_than[0]',
            'min_per_purchase'  => 'permit_empty|integer|greater_than[0]',
            'max_per_purchase'  => 'permit_empty|integer|greater_than[0]',
            'draw_date'         => 'permit_empty|valid_date',
            'status'            => 'required|in_list[active,finished,cancelled]',
        ];

        $image = $this->request->getFile('image');
        if ($image && $image->isValid()) {
            $rules['image'] = 'max_size[image,2048]|is_image[image]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Processa upload de imagem
        $imageName = $raffle->image;
        
        if ($image && $image->isValid() && !$image->hasMoved()) {
            // Remove imagem antiga
            if ($raffle->image && file_exists(FCPATH . 'uploads/' . $raffle->image)) {
                unlink(FCPATH . 'uploads/' . $raffle->image);
            }
            
            $imageName = $image->getRandomName();
            $image->move(FCPATH . 'uploads', $imageName);
        }

        $raffle->fill([
            'name'              => $this->request->getPost('name'),
            'description'       => $this->request->getPost('description'),
            'prize_description' => $this->request->getPost('prize_description'),
            'price'             => $this->request->getPost('price'),
            'min_per_purchase'  => $this->request->getPost('min_per_purchase') ?: 1,
            'max_per_purchase'  => $this->request->getPost('max_per_purchase') ?: 100,
            'draw_date'         => $this->request->getPost('draw_date') ?: null,
            'image'             => $imageName,
            'status'            => $this->request->getPost('status'),
            'is_featured'       => $this->request->getPost('is_featured') ? 1 : 0,
        ]);

        if ($this->raffleModel->save($raffle)) {
            return redirect()->to('admin/raffles/' . $id)->with('success', 'Rifa atualizada com sucesso!');
        }

        return redirect()->back()->withInput()->with('error', 'Erro ao atualizar rifa.');
    }

    /**
     * Exclui uma rifa
     */
    public function delete($id = null)
    {
        $raffle = $this->raffleModel->find($id);

        if (!$raffle) {
            return redirect()->to('admin/raffles')->with('error', 'Rifa não encontrada.');
        }

        // Verifica se tem números vendidos
        $soldCount = $this->numberModel->countByStatus($id, 'sold');
        if ($soldCount > 0) {
            return redirect()->to('admin/raffles')->with('error', 'Não é possível excluir uma rifa com números vendidos.');
        }

        // Remove imagem
        if ($raffle->image && file_exists(FCPATH . 'uploads/' . $raffle->image)) {
            unlink(FCPATH . 'uploads/' . $raffle->image);
        }

        if ($this->raffleModel->delete($id)) {
            return redirect()->to('admin/raffles')->with('success', 'Rifa excluída com sucesso!');
        }

        return redirect()->to('admin/raffles')->with('error', 'Erro ao excluir rifa.');
    }

    /**
     * Gera números para a rifa
     */
    public function generateNumbers($id = null)
    {
        $raffle = $this->raffleModel->find($id);

        if (!$raffle) {
            return redirect()->to('admin/raffles')->with('error', 'Rifa não encontrada.');
        }

        if ($raffle->numbers_generated) {
            return redirect()->to('admin/raffles/' . $id)->with('error', 'Os números já foram gerados para esta rifa.');
        }

        $this->numberModel->generateNumbers($id, $raffle->quantity, $raffle->start_number);
        $this->raffleModel->update($id, ['numbers_generated' => 1]);

        return redirect()->to('admin/raffles/' . $id)->with('success', 'Números gerados com sucesso!');
    }

    /**
     * Realiza o sorteio
     */
    public function draw($id = null)
    {
        $raffle = $this->raffleModel->find($id);

        if (!$raffle) {
            return redirect()->to('admin/raffles')->with('error', 'Rifa não encontrada.');
        }

        $winnerModel = new WinnerModel();

        // Verifica se já foi sorteada
        if ($winnerModel->hasWinner($id)) {
            return redirect()->to('admin/raffles/' . $id)->with('error', 'Esta rifa já foi sorteada.');
        }

        // Verifica se tem números vendidos
        $soldCount = $this->numberModel->countByStatus($id, 'sold');
        if ($soldCount === 0) {
            return redirect()->to('admin/raffles/' . $id)->with('error', 'Não há números vendidos para sortear.');
        }

        // Sorteia um número aleatório
        $winningNumber = $this->numberModel->drawRandomSoldNumber($id);

        if (!$winningNumber) {
            return redirect()->to('admin/raffles/' . $id)->with('error', 'Erro ao realizar o sorteio.');
        }

        // Busca dados do pedido
        $orderModel = new \App\Models\OrderModel();
        $order = $orderModel->find($winningNumber->order_id);

        // Registra o ganhador
        $winner = [
            'raffle_id'        => $id,
            'raffle_number_id' => $winningNumber->id,
            'user_id'          => $winningNumber->user_id,
            'order_id'         => $winningNumber->order_id,
            'winning_number'   => $winningNumber->number,
            'winner_name'      => $order->customer_name,
            'winner_email'     => $order->customer_email,
            'winner_phone'     => $order->customer_phone,
            'prize_position'   => 1,
            'prize_description'=> $raffle->prize_description,
            'draw_date'        => date('Y-m-d H:i:s'),
        ];

        $winnerModel->insert($winner);

        // Atualiza rifa
        $this->raffleModel->update($id, [
            'status'         => 'finished',
            'winning_number' => $winningNumber->number,
        ]);

        return redirect()->to('admin/raffles/' . $id)->with('success', "Sorteio realizado! Número vencedor: {$winningNumber->number}");
    }
}
