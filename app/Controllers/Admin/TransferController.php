<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RaffleModel;
use App\Models\TransferModel;

class TransferController extends BaseController
{
    protected TransferModel $transferModel;
    protected RaffleModel $raffleModel;

    public function __construct()
    {
        $this->transferModel = new TransferModel();
        $this->raffleModel = new RaffleModel();
    }

    public function index()
    {
        $status = (string) ($this->request->getGet('status') ?? '');
        $provider = (string) ($this->request->getGet('provider') ?? '');
        $raffleId = $this->request->getGet('raffle_id');
        $raffleId = is_numeric($raffleId) ? (int) $raffleId : null;

        $filters = [
            'status' => $status !== '' ? $status : null,
            'provider' => $provider !== '' ? $provider : null,
            'raffle_id' => $raffleId,
        ];

        $transfers = $this->transferModel->getForAdmin(array_filter($filters, static fn ($v) => $v !== null));

        // Restringe para não-admin apenas às rifas do próprio usuário
        $role = session()->get('user_role');
        if ($role !== 'admin') {
            $userId = (int) session()->get('user_id');
            $myRaffles = $this->raffleModel->select('id')->where('user_id', $userId)->findAll();
            $ids = array_map(static fn ($r) => (int) $r->id, $myRaffles);

            $transfers = array_values(array_filter($transfers, static function ($t) use ($ids) {
                return in_array((int) ($t->raffle_id ?? 0), $ids, true);
            }));
        }

        return view('admin/transfers/index', [
            'transfers' => $transfers,
            'filters' => [
                'status' => $status,
                'provider' => $provider,
                'raffle_id' => $raffleId,
            ],
        ]);
    }

    public function show($id = null)
    {
        $transfer = $this->transferModel->find($id);
        if (!$transfer) {
            return redirect()->to('admin/transfers')->with('error', 'Transferência não encontrada.');
        }

        // Permissão (não-admin só vê transfers das próprias rifas)
        $role = session()->get('user_role');
        if ($role !== 'admin') {
            $raffle = $this->raffleModel->find((int) $transfer->raffle_id);
            if (!$raffle || (int) $raffle->user_id !== (int) session()->get('user_id')) {
                return redirect()->to('admin/transfers')->with('error', 'Você não tem permissão para acessar esta transferência.');
            }
        }

        return view('admin/transfers/show', [
            'transfer' => $transfer,
            'payload' => $transfer->getProviderPayload(),
            'pixKey' => $transfer->getPixKey(),
        ]);
    }
}
