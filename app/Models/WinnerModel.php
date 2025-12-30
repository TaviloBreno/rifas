<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Winner;

class WinnerModel extends Model
{
    protected $table            = 'winners';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Winner::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'raffle_id',
        'raffle_number_id',
        'user_id',
        'order_id',
        'winning_number',
        'winner_name',
        'winner_email',
        'winner_phone',
        'prize_position',
        'prize_description',
        'is_claimed',
        'claimed_at',
        'draw_date',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Retorna ganhadores por rifa
     */
    public function getByRaffle(int $raffleId)
    {
        return $this->where('raffle_id', $raffleId)
                    ->orderBy('prize_position', 'ASC')
                    ->findAll();
    }

    /**
     * Retorna ganhadores por usuário
     */
    public function getByUser(int $userId)
    {
        return $this->select('winners.*, raffles.name as raffle_name')
                    ->join('raffles', 'raffles.id = winners.raffle_id')
                    ->where('winners.user_id', $userId)
                    ->orderBy('winners.draw_date', 'DESC')
                    ->findAll();
    }

    /**
     * Marca prêmio como resgatado
     */
    public function markAsClaimed(int $winnerId): bool
    {
        return $this->update($winnerId, [
            'is_claimed' => 1,
            'claimed_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Verifica se rifa já tem ganhador
     */
    public function hasWinner(int $raffleId): bool
    {
        return $this->where('raffle_id', $raffleId)->countAllResults() > 0;
    }

    /**
     * Retorna ganhadores recentes
     */
    public function getRecent(int $limit = 10)
    {
        return $this->select('winners.*, raffles.name as raffle_name, raffles.image as raffle_image')
                    ->join('raffles', 'raffles.id = winners.raffle_id')
                    ->orderBy('winners.draw_date', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Lista ganhadores com detalhes para admin
     */
    public function getForAdmin()
    {
        return $this->select('winners.*, raffles.name as raffle_name')
                    ->join('raffles', 'raffles.id = winners.raffle_id')
                    ->orderBy('winners.draw_date', 'DESC')
                    ->findAll();
    }
}
