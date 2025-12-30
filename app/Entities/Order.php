<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Order extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at', 'paid_at', 'expires_at'];
    protected $casts   = [
        'id'            => 'integer',
        'user_id'       => '?integer',
        'raffle_id'     => 'integer',
        'total_numbers' => 'integer',
        'total_amount'  => 'float',
    ];

    /**
     * Retorna o valor total formatado
     */
    public function getFormattedAmount(): string
    {
        return 'R$ ' . number_format($this->attributes['total_amount'], 2, ',', '.');
    }

    /**
     * Retorna o status traduzido
     */
    public function getStatusLabel(): string
    {
        $labels = [
            'pending'   => 'Pendente',
            'paid'      => 'Pago',
            'cancelled' => 'Cancelado',
            'expired'   => 'Expirado',
        ];

        return $labels[$this->attributes['status']] ?? 'Desconhecido';
    }

    /**
     * Retorna a classe CSS do status
     */
    public function getStatusClass(): string
    {
        $classes = [
            'pending'   => 'is-warning',
            'paid'      => 'is-success',
            'cancelled' => 'is-danger',
            'expired'   => 'is-dark',
        ];

        return $classes[$this->attributes['status']] ?? 'is-light';
    }

    /**
     * Retorna ícone do status
     */
    public function getStatusIcon(): string
    {
        $icons = [
            'pending'   => 'fa-clock',
            'paid'      => 'fa-check-circle',
            'cancelled' => 'fa-times-circle',
            'expired'   => 'fa-hourglass-end',
        ];

        return $icons[$this->attributes['status']] ?? 'fa-question-circle';
    }

    /**
     * Verifica se está pendente
     */
    public function isPending(): bool
    {
        return $this->attributes['status'] === 'pending';
    }

    /**
     * Verifica se está pago
     */
    public function isPaid(): bool
    {
        return $this->attributes['status'] === 'paid';
    }

    /**
     * Verifica se está cancelado
     */
    public function isCancelled(): bool
    {
        return $this->attributes['status'] === 'cancelled';
    }

    /**
     * Verifica se expirou
     */
    public function isExpired(): bool
    {
        if ($this->attributes['status'] === 'expired') {
            return true;
        }

        if ($this->attributes['status'] !== 'pending') {
            return false;
        }

        if (empty($this->attributes['expires_at'])) {
            return false;
        }

        return strtotime($this->attributes['expires_at']) < time();
    }

    /**
     * Retorna a data de criação formatada
     */
    public function getFormattedCreatedAt(): string
    {
        if (empty($this->attributes['created_at'])) {
            return '-';
        }

        return date('d/m/Y H:i', strtotime($this->attributes['created_at']));
    }

    /**
     * Retorna a data de pagamento formatada
     */
    public function getFormattedPaidAt(): string
    {
        if (empty($this->attributes['paid_at'])) {
            return '-';
        }

        return date('d/m/Y H:i', strtotime($this->attributes['paid_at']));
    }

    /**
     * Retorna tempo restante para pagamento
     */
    public function getRemainingTime(): string
    {
        if (empty($this->attributes['expires_at']) || $this->isExpired()) {
            return 'Expirado';
        }

        $diff = strtotime($this->attributes['expires_at']) - time();
        
        if ($diff <= 0) {
            return 'Expirado';
        }

        $minutes = floor($diff / 60);
        $seconds = $diff % 60;

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Retorna o telefone formatado
     */
    public function getFormattedPhone(): string
    {
        $phone = preg_replace('/\D/', '', $this->attributes['customer_phone'] ?? '');
        
        if (strlen($phone) === 11) {
            return sprintf('(%s) %s-%s', 
                substr($phone, 0, 2),
                substr($phone, 2, 5),
                substr($phone, 7)
            );
        }
        
        return $this->attributes['customer_phone'] ?? '';
    }
}
