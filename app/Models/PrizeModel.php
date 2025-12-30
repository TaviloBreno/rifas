<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Prize;

class PrizeModel extends Model
{
    protected $table            = 'prizes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Prize::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'raffle_id',
        'name',
        'description',
        'image',
        'position',
        'value',
        'winner_number',
        'winner_name',
        'claimed_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'raffle_id'   => 'required|integer|is_not_unique[raffles.id]',
        'name'        => 'required|min_length[3]|max_length[255]',
        'description' => 'permit_empty|max_length[5000]',
        'position'    => 'required|integer|greater_than[0]',
        'value'       => 'permit_empty|decimal',
    ];

    protected $validationMessages = [
        'raffle_id' => [
            'required'      => 'A rifa é obrigatória.',
            'is_not_unique' => 'A rifa selecionada não existe.',
        ],
        'name' => [
            'required'   => 'O nome do prêmio é obrigatório.',
            'min_length' => 'O nome deve ter pelo menos 3 caracteres.',
        ],
        'position' => [
            'required'     => 'A posição é obrigatória.',
            'greater_than' => 'A posição deve ser maior que zero.',
        ],
    ];

    /**
     * Retorna todos os prêmios de uma rifa
     */
    public function getByRaffle(int $raffleId)
    {
        return $this->where('raffle_id', $raffleId)
                    ->orderBy('position', 'ASC')
                    ->findAll();
    }

    /**
     * Retorna o próximo número de posição para uma rifa
     */
    public function getNextPosition(int $raffleId): int
    {
        $result = $this->selectMax('position')
                       ->where('raffle_id', $raffleId)
                       ->first();

        return ($result->position ?? 0) + 1;
    }

    /**
     * Define o ganhador de um prêmio
     */
    public function setWinner(int $prizeId, int $winnerNumber, string $winnerName): bool
    {
        return $this->update($prizeId, [
            'winner_number' => $winnerNumber,
            'winner_name'   => $winnerName,
        ]);
    }

    /**
     * Marca o prêmio como reivindicado
     */
    public function markAsClaimed(int $prizeId): bool
    {
        return $this->update($prizeId, [
            'claimed_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Retorna prêmios com ganhadores
     */
    public function getWinners(int $limit = 10)
    {
        return $this->select('prizes.*, raffles.name as raffle_name')
                    ->join('raffles', 'raffles.id = prizes.raffle_id')
                    ->where('prizes.winner_number IS NOT NULL')
                    ->orderBy('prizes.updated_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}
