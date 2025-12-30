<?php

namespace App\Models;

use App\Entities\Transfer;
use CodeIgniter\Model;

class TransferModel extends Model
{
    protected $table            = 'transfers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Transfer::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'raffle_id',
        'winner_id',
        'order_id',
        'recipient_type',
        'recipient_name',
        'recipient_email',
        'pix_key_type',
        'pix_key_encrypted',
        'amount',
        'currency',
        'provider',
        'provider_reference',
        'provider_payload_encrypted',
        'status',
        'error_message',
        'scheduled_at',
        'processed_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function existsForWinner(int $winnerId): bool
    {
        return $this->where('winner_id', $winnerId)->countAllResults() > 0;
    }

    public function getPending(int $limit = 20)
    {
        return $this->where('status', 'pending')
            ->orderBy('created_at', 'ASC')
            ->limit($limit)
            ->findAll();
    }

    public function getForAdmin(array $filters = [])
    {
        $builder = $this->select('transfers.*,
                raffles.name as raffle_name,
                winners.winning_number as winner_number')
            ->join('raffles', 'raffles.id = transfers.raffle_id', 'left')
            ->join('winners', 'winners.id = transfers.winner_id', 'left');

        if (!empty($filters['status'])) {
            $builder->where('transfers.status', $filters['status']);
        }

        if (!empty($filters['provider'])) {
            $builder->where('transfers.provider', $filters['provider']);
        }

        if (!empty($filters['raffle_id'])) {
            $builder->where('transfers.raffle_id', $filters['raffle_id']);
        }

        return $builder->orderBy('transfers.created_at', 'DESC')->findAll();
    }
}
