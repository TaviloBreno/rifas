<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\RaffleEntry;

class RaffleEntryModel extends Model
{
    protected $table            = 'raffles_entries';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = RaffleEntry::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'ticket_id',
        'raffle_id',
        'raffle_number_id',
        'number',
        'status',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'ticket_id' => 'required|integer|is_not_unique[tickets.id]',
        'raffle_id' => 'required|integer|is_not_unique[raffles.id]',
        'number'    => 'required|integer|greater_than_equal_to[0]',
        'status'    => 'required|in_list[reserved,sold]',
    ];

    public function getByTicket(int $ticketId)
    {
        return $this->where('ticket_id', $ticketId)
            ->orderBy('number', 'ASC')
            ->findAll();
    }
}
