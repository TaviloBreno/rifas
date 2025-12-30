<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class RaffleNumber extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'reserved_until'];
    protected $casts   = [
        'id'        => 'integer',
        'raffle_id' => 'integer',
        'number'    => 'integer',
        'user_id'   => '?integer',
        'order_id'  => '?integer',
    ];

    /**
     * Retorna o número formatado com zeros à esquerda
     */
    public function getFormattedNumber(int $digits = 4): string
    {
        return str_pad($this->attributes['number'], $digits, '0', STR_PAD_LEFT);
    }

    /**
     * Retorna o status traduzido
     */
    public function getStatusLabel(): string
    {
        $labels = [
            'available' => 'Disponível',
            'reserved'  => 'Reservado',
            'sold'      => 'Vendido',
        ];

        return $labels[$this->attributes['status']] ?? 'Desconhecido';
    }

    /**
     * Retorna a classe CSS do status
     */
    public function getStatusClass(): string
    {
        $classes = [
            'available' => 'is-success',
            'reserved'  => 'is-warning',
            'sold'      => 'is-danger',
        ];

        return $classes[$this->attributes['status']] ?? 'is-light';
    }

    /**
     * Verifica se está disponível
     */
    public function isAvailable(): bool
    {
        return $this->attributes['status'] === 'available';
    }

    /**
     * Verifica se está reservado
     */
    public function isReserved(): bool
    {
        return $this->attributes['status'] === 'reserved';
    }

    /**
     * Verifica se está vendido
     */
    public function isSold(): bool
    {
        return $this->attributes['status'] === 'sold';
    }

    /**
     * Verifica se a reserva expirou
     */
    public function isReservationExpired(): bool
    {
        if ($this->attributes['status'] !== 'reserved') {
            return false;
        }

        if (empty($this->attributes['reserved_until'])) {
            return false;
        }

        return strtotime($this->attributes['reserved_until']) < time();
    }
}
