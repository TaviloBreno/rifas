<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Ticket;

class TicketModel extends Model
{
    protected $table            = 'tickets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Ticket::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_id',
        'user_id',
        'raffle_id',
        'code',
        'status',
        'payment_method',
        'payment_id',
        'paid_at',
        'expires_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'raffle_id' => 'required|integer|is_not_unique[raffles.id]',
        'code'      => 'required|min_length[6]|max_length[32]',
        'status'    => 'required|in_list[pending,paid,cancelled,expired]',
    ];

    public function findByCode(string $code)
    {
        return $this->where('code', $code)->first();
    }

    public function findByOrderId(int $orderId)
    {
        return $this->where('order_id', $orderId)->first();
    }
}
