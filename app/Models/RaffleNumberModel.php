<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\RaffleNumber;

class RaffleNumberModel extends Model
{
    protected $table            = 'raffle_numbers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = RaffleNumber::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'raffle_id',
        'number',
        'status',
        'user_id',
        'order_id',
        'reserved_until',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'raffle_id' => 'required|integer',
        'number'    => 'required|integer',
        'status'    => 'permit_empty|in_list[available,reserved,sold]',
    ];

    /**
     * Gera os números para uma rifa
     */
    public function generateNumbers(int $raffleId, int $quantity, int $startNumber = 0): bool
    {
        $numbers = [];
        $now = date('Y-m-d H:i:s');

        for ($i = 0; $i < $quantity; $i++) {
            $numbers[] = [
                'raffle_id'  => $raffleId,
                'number'     => $startNumber + $i,
                'status'     => 'available',
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Insere em lotes de 500 para evitar problemas de memória
            if (count($numbers) >= 500) {
                $this->insertBatch($numbers);
                $numbers = [];
            }
        }

        // Insere os números restantes
        if (!empty($numbers)) {
            $this->insertBatch($numbers);
        }

        return true;
    }

    /**
     * Retorna números disponíveis de uma rifa
     */
    public function getAvailableByRaffle(int $raffleId)
    {
        return $this->where('raffle_id', $raffleId)
                    ->where('status', 'available')
                    ->orderBy('number', 'ASC')
                    ->findAll();
    }

    /**
     * Retorna números vendidos de uma rifa
     */
    public function getSoldByRaffle(int $raffleId)
    {
        return $this->where('raffle_id', $raffleId)
                    ->where('status', 'sold')
                    ->orderBy('number', 'ASC')
                    ->findAll();
    }

    /**
     * Retorna números reservados de uma rifa
     */
    public function getReservedByRaffle(int $raffleId)
    {
        return $this->where('raffle_id', $raffleId)
                    ->where('status', 'reserved')
                    ->orderBy('number', 'ASC')
                    ->findAll();
    }

    /**
     * Retorna todos os números de uma rifa
     */
    public function getAllByRaffle(int $raffleId)
    {
        return $this->where('raffle_id', $raffleId)
                    ->orderBy('number', 'ASC')
                    ->findAll();
    }

    /**
     * Conta números por status
     */
    public function countByStatus(int $raffleId, string $status): int
    {
        return $this->where('raffle_id', $raffleId)
                    ->where('status', $status)
                    ->countAllResults();
    }

    /**
     * Reserva números para um pedido
     */
    public function reserveNumbers(array $numberIds, int $orderId, int $userId = null, int $minutes = 30): bool
    {
        $expiresAt = date('Y-m-d H:i:s', strtotime("+{$minutes} minutes"));

        return $this->whereIn('id', $numberIds)
                    ->where('status', 'available')
                    ->set([
                        'status'         => 'reserved',
                        'order_id'       => $orderId,
                        'user_id'        => $userId,
                        'reserved_until' => $expiresAt,
                    ])
                    ->update();
    }

    /**
     * Confirma venda dos números
     */
    public function confirmSale(int $orderId): bool
    {
        return $this->where('order_id', $orderId)
                    ->set([
                        'status'         => 'sold',
                        'reserved_until' => null,
                    ])
                    ->update();
    }

    /**
     * Libera números reservados (cancela reserva)
     */
    public function releaseNumbers(int $orderId): bool
    {
        return $this->where('order_id', $orderId)
                    ->set([
                        'status'         => 'available',
                        'order_id'       => null,
                        'user_id'        => null,
                        'reserved_until' => null,
                    ])
                    ->update();
    }

    /**
     * Libera números com reserva expirada
     */
    public function releaseExpiredReservations(): int
    {
        $this->where('status', 'reserved')
             ->where('reserved_until <', date('Y-m-d H:i:s'))
             ->set([
                 'status'         => 'available',
                 'order_id'       => null,
                 'user_id'        => null,
                 'reserved_until' => null,
             ])
             ->update();

        return $this->db->affectedRows();
    }

    /**
     * Retorna números por pedido
     */
    public function getByOrder(int $orderId)
    {
        return $this->where('order_id', $orderId)
                    ->orderBy('number', 'ASC')
                    ->findAll();
    }

    /**
     * Retorna estatísticas de uma rifa
     */
    public function getStats(int $raffleId): array
    {
        return [
            'total'     => $this->where('raffle_id', $raffleId)->countAllResults(false),
            'available' => $this->where('raffle_id', $raffleId)->where('status', 'available')->countAllResults(false),
            'reserved'  => $this->where('raffle_id', $raffleId)->where('status', 'reserved')->countAllResults(false),
            'sold'      => $this->where('raffle_id', $raffleId)->where('status', 'sold')->countAllResults(false),
        ];
    }

    /**
     * Busca números específicos por array de números
     */
    public function getByNumbers(int $raffleId, array $numbers)
    {
        return $this->where('raffle_id', $raffleId)
                    ->whereIn('number', $numbers)
                    ->findAll();
    }

    /**
     * Sorteia um número vendido aleatoriamente
     */
    public function drawRandomSoldNumber(int $raffleId): ?RaffleNumber
    {
        return $this->where('raffle_id', $raffleId)
                    ->where('status', 'sold')
                    ->orderBy('RAND()')
                    ->first();
    }
}
