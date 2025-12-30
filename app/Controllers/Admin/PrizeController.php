<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PrizeModel;
use App\Models\RaffleModel;

class PrizeController extends BaseController
{
    protected PrizeModel $prizeModel;
    protected RaffleModel $raffleModel;

    public function __construct()
    {
        $this->prizeModel  = new PrizeModel();
        $this->raffleModel = new RaffleModel();
    }

    public function index(int $raffleId)
    {
        $raffle = $this->raffleModel->find($raffleId);

        if (!$raffle) {
            return redirect()->to('admin/raffles')->with('error', 'Rifa não encontrada.');
        }

        if (!empty($raffle->user_id) && $raffle->user_id !== session()->get('user_id')) {
            return redirect()->to('admin/raffles')->with('error', 'Acesso negado.');
        }

        $prizes = $this->prizeModel->getByRaffle($raffleId);

        return view('admin/prizes/index', [
            'raffle' => $raffle,
            'prizes' => $prizes,
        ]);
    }

    public function new(int $raffleId)
    {
        $raffle = $this->raffleModel->find($raffleId);

        if (!$raffle) {
            return redirect()->to('admin/raffles')->with('error', 'Rifa não encontrada.');
        }

        if (!empty($raffle->user_id) && $raffle->user_id !== session()->get('user_id')) {
            return redirect()->to('admin/raffles')->with('error', 'Acesso negado.');
        }

        return view('admin/prizes/new', [
            'raffle'       => $raffle,
            'nextPosition' => $this->prizeModel->getNextPosition($raffleId),
        ]);
    }

    public function create(int $raffleId)
    {
        $raffle = $this->raffleModel->find($raffleId);

        if (!$raffle) {
            return redirect()->to('admin/raffles')->with('error', 'Rifa não encontrada.');
        }

        if (!empty($raffle->user_id) && $raffle->user_id !== session()->get('user_id')) {
            return redirect()->to('admin/raffles')->with('error', 'Acesso negado.');
        }

        $data = [
            'raffle_id'   => $raffleId,
            'name'        => (string) $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'position'    => (int) $this->request->getPost('position'),
            'value'       => $this->request->getPost('value') ?: null,
        ];

        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $uploadPath = FCPATH . 'uploads/prizes';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $image->move($uploadPath, $newName);
            $data['image'] = $newName;
        }

        if ($this->prizeModel->insert($data)) {
            return redirect()->to("admin/raffles/{$raffleId}/prizes")
                ->with('success', 'Prêmio cadastrado com sucesso!');
        }

        return redirect()->back()
            ->withInput()
            ->with('errors', $this->prizeModel->errors());
    }

    public function edit(int $raffleId, int $prizeId)
    {
        $raffle = $this->raffleModel->find($raffleId);

        if (!$raffle) {
            return redirect()->to('admin/raffles')->with('error', 'Rifa não encontrada.');
        }

        if (!empty($raffle->user_id) && $raffle->user_id !== session()->get('user_id')) {
            return redirect()->to('admin/raffles')->with('error', 'Acesso negado.');
        }

        $prize = $this->prizeModel->find($prizeId);
        if (!$prize || (int) $prize->raffle_id !== $raffleId) {
            return redirect()->to("admin/raffles/{$raffleId}/prizes")
                ->with('error', 'Prêmio não encontrado.');
        }

        return view('admin/prizes/edit', [
            'raffle' => $raffle,
            'prize'  => $prize,
        ]);
    }

    public function update(int $raffleId, int $prizeId)
    {
        $raffle = $this->raffleModel->find($raffleId);

        if (!$raffle) {
            return redirect()->to('admin/raffles')->with('error', 'Rifa não encontrada.');
        }

        if (!empty($raffle->user_id) && $raffle->user_id !== session()->get('user_id')) {
            return redirect()->to('admin/raffles')->with('error', 'Acesso negado.');
        }

        $prize = $this->prizeModel->find($prizeId);
        if (!$prize || (int) $prize->raffle_id !== $raffleId) {
            return redirect()->to("admin/raffles/{$raffleId}/prizes")
                ->with('error', 'Prêmio não encontrado.');
        }

        $data = [
            'name'        => (string) $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'position'    => (int) $this->request->getPost('position'),
            'value'       => $this->request->getPost('value') ?: null,
        ];

        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            if ($prize->image) {
                $old = FCPATH . 'uploads/prizes/' . $prize->image;
                if (is_file($old)) {
                    unlink($old);
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
            return redirect()->to("admin/raffles/{$raffleId}/prizes")
                ->with('success', 'Prêmio atualizado com sucesso!');
        }

        return redirect()->back()
            ->withInput()
            ->with('errors', $this->prizeModel->errors());
    }

    public function delete(int $raffleId, int $prizeId)
    {
        $raffle = $this->raffleModel->find($raffleId);

        if (!$raffle) {
            return redirect()->to('admin/raffles')->with('error', 'Rifa não encontrada.');
        }

        if (!empty($raffle->user_id) && $raffle->user_id !== session()->get('user_id')) {
            return redirect()->to('admin/raffles')->with('error', 'Acesso negado.');
        }

        $prize = $this->prizeModel->find($prizeId);
        if (!$prize || (int) $prize->raffle_id !== $raffleId) {
            return redirect()->to("admin/raffles/{$raffleId}/prizes")
                ->with('error', 'Prêmio não encontrado.');
        }

        if ($prize->image) {
            $path = FCPATH . 'uploads/prizes/' . $prize->image;
            if (is_file($path)) {
                unlink($path);
            }
        }

        if ($this->prizeModel->delete($prizeId)) {
            return redirect()->to("admin/raffles/{$raffleId}/prizes")
                ->with('success', 'Prêmio excluído com sucesso!');
        }

        return redirect()->back()->with('error', 'Erro ao excluir prêmio.');
    }
}
