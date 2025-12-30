<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Ticket extends Entity
{
    protected $dates = ['created_at', 'updated_at', 'paid_at', 'expires_at'];
    protected $casts = [
        'id'       => 'integer',
        'order_id' => 'integer',
        'user_id'  => 'integer',
        'raffle_id'=> 'integer',
    ];

    public function isPaid(): bool
    {
        return ($this->attributes['status'] ?? null) === 'paid';
    }

    public function isPending(): bool
    {
        return ($this->attributes['status'] ?? null) === 'pending';
    }

    public function isExpired(): bool
    {
        $expiresAt = $this->attributes['expires_at'] ?? null;
        if (!$expiresAt) {
            return false;
        }

        return strtotime((string) $expiresAt) < time();
    }
}
