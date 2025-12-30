<?php

namespace App\Controllers;

use App\Models\PrizeModel;
use App\Models\RaffleModel;

class PrizeController extends BaseController
{
    protected PrizeModel $prizeModel;
    protected RaffleModel $raffleModel;

    public function __construct()
    {
        $this->prizeModel = new PrizeModel();
        $this->raffleModel = new RaffleModel();
    }

    /**
     * Lista os prêmios de uma rifa
     */
    public function index(int $raffleId)
    {
        $raffle = $this->raffleModel->find($raffleId);

        if (!$raffle) {
            return redirect()->to('raffles')->with('error', 'Rifa não encontrada.');
        }

        // Verifica se a rifa pertence ao usuário logado
        if ($raffle->user_id !== session()->get('user_id')) {
            return redirect()->to('raffles')->with('error', 'Acesso negado.');
        }

        $prizes = $this->prizeModel->getByRaffle($raffleId);

        return view('prizes/index', [
            'raffle' => $raffle,
            'prizes' => $prizes,
        ]);
    }

    /**
     * Formulário de criação de prêmio
     */
    public function new(int $raffleId)
    {
        $raffle = $this->raffleModel->find($raffleId);

        if (!$raffle) {
            return redirect()->to('raffles')->with('error', 'Rifa não encontrada.');
        }

        if ($raffle->user_id !== session()->get('user_id')) {
            return redirect()->to('raffles')->with('error', 'Acesso negado.');
        }

        $nextPosition = $this->prizeModel->getNextPosition($raffleId);

        return view('prizes/new', [
            'raffle'       => $raffle,
            'nextPosition' => $nextPosition,
        ]);
    }

    /**
     * Cria um novo prêmio
     */
    public function create(int $raffleId)
    {
        $raffle = $this->raffleModel->find($raffleId);

        if (!$raffle) {
            return redirect()->to('raffles')->with('error', 'Rifa não encontrada.');
        }

        if ($raffle->user_id !== session()->get('user_id')) {
            return redirect()->to('raffles')->with('error', 'Acesso negado.');
        }

        $data = [
            'raffle_id'   => $raffleId,
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'position'    => $this->request->getPost('position'),
            'value'       => $this->request->getPost('value') ?: null,
        ];

        // Upload da imagem
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            
            // Cria diretório se não existir
            $uploadPath = FCPATH . 'uploads/prizes';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            $image->move($uploadPath, $newName);
            $data['image'] = $newName;
        }

        if ($this->prizeModel->insert($data)) {
            return redirect()->to("raffles/{$raffleId}/prizes")
                           ->with('success', 'Prêmio cadastrado com sucesso!');
        }

        return redirect()->back()
                        ->withInput()
                        ->with('errors', $this->prizeModel->errors());
    }

    /**
     * Formulário de edição de prêmio
     */
    public function edit(int $raffleId, int $prizeId)
    {
        $raffle = $this->raffleModel->find($raffleId);

        if (!$raffle || $raffle->user_id !== session()->get('user_id')) {
            return redirect()->to('raffles')->with('error', 'Acesso negado.');
        }

        $prize = $this->prizeModel->find($prizeId);

        if (!$prize || $prize->raffle_id !== $raffleId) {
            return redirect()->to("raffles/{$raffleId}/prizes")
                           ->with('error', 'Prêmio não encontrado.');
        }

        return view('prizes/edit', [
            'raffle' => $raffle,
            'prize'  => $prize,
        ]);
    }

    /**
     * Atualiza um prêmio
     */
    public function update(int $raffleId, int $prizeId)
    {
        $raffle = $this->raffleModel->find($raffleId);

        if (!$raffle || $raffle->user_id !== session()->get('user_id')) {
            return redirect()->to('raffles')->with('error', 'Acesso negado.');
        }

        $prize = $this->prizeModel->find($prizeId);

        if (!$prize || $prize->raffle_id !== $raffleId) {
            return redirect()->to("raffles/{$raffleId}/prizes")
                           ->with('error', 'Prêmio não encontrado.');
        }

        $data = [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'position'    => $this->request->getPost('position'),
            'value'       => $this->request->getPost('value') ?: null,
        ];

        // Upload da nova imagem
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            // Remove imagem antiga
            if ($prize->image) {
                $oldImage = FCPATH . 'uploads/prizes/' . $prize->image;
                if (file_exists($oldImage)) {
                    unlink($oldImage);
                }
            }

            $newName = $image->getRandomName();
            $uploadPath = FCPATH . 'uploads/prizes';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $image->move($uploadPath, $newName);
            $data['image'] = $newName;
        }

        if ($this->prizeModel->update($prizeId, $data)) {
            return redirect()->to("raffles/{$raffleId}/prizes")
                           ->with('success', 'Prêmio atualizado com sucesso!');
        }

        return redirect()->back()
                        ->withInput()
                        ->with('errors', $this->prizeModel->errors());
    }

    /**
     * Exclui um prêmio
     */
    public function delete(int $raffleId, int $prizeId)
    {
        $raffle = $this->raffleModel->find($raffleId);

        if (!$raffle || $raffle->user_id !== session()->get('user_id')) {
            return redirect()->to('raffles')->with('error', 'Acesso negado.');
        }

        $prize = $this->prizeModel->find($prizeId);

        if (!$prize || $prize->raffle_id !== $raffleId) {
            return redirect()->to("raffles/{$raffleId}/prizes")
                           ->with('error', 'Prêmio não encontrado.');
        }

        // Remove imagem
        if ($prize->image) {
            $imagePath = FCPATH . 'uploads/prizes/' . $prize->image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if ($this->prizeModel->delete($prizeId)) {
            return redirect()->to("raffles/{$raffleId}/prizes")
                           ->with('success', 'Prêmio excluído com sucesso!');
        }

        return redirect()->back()->with('error', 'Erro ao excluir prêmio.');
    }
}
