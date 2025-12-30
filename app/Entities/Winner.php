<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Winner extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'draw_date', 'claimed_at'];
    protected $casts   = [
        'id'              => 'integer',
        'raffle_id'       => 'integer',
        'raffle_number_id'=> 'integer',
        'user_id'         => '?integer',
        'order_id'        => 'integer',
        'winning_number'  => 'integer',
        'prize_position'  => 'integer',
        'is_claimed'      => 'boolean',
    ];

    /**
     * Retorna o número vencedor formatado
     */
    public function getFormattedNumber(int $digits = 4): string
    {
        return str_pad($this->attributes['winning_number'], $digits, '0', STR_PAD_LEFT);
    }

    /**
     * Retorna a posição do prêmio
     */
    public function getPrizePositionLabel(): string
    {
        $labels = [
            1 => '1º Prêmio',
            2 => '2º Prêmio',
            3 => '3º Prêmio',
        ];

        return $labels[$this->attributes['prize_position']] ?? $this->attributes['prize_position'] . 'º Prêmio';
    }

    /**
     * Verifica se foi resgatado
     */
    public function isClaimed(): bool
    {
        return (bool) $this->attributes['is_claimed'];
    }

    /**
     * Retorna a data do sorteio formatada
     */
    public function getFormattedDrawDate(): string
    {
        if (empty($this->attributes['draw_date'])) {
            return '-';
        }

        return date('d/m/Y H:i', strtotime($this->attributes['draw_date']));
    }

    /**
     * Retorna a data de resgate formatada
     */
    public function getFormattedClaimedAt(): string
    {
        if (empty($this->attributes['claimed_at'])) {
            return '-';
        }

        return date('d/m/Y H:i', strtotime($this->attributes['claimed_at']));
    }
}
