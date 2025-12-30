<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Transfer extends Entity
{
    protected $dates = ['scheduled_at', 'processed_at', 'created_at', 'updated_at'];
    protected $casts = [
        'id' => 'integer',
        'raffle_id' => 'integer',
        'winner_id' => 'integer',
        'order_id' => 'integer',
        'amount' => 'float',
    ];

    public function setPixKey(?string $pixKey): self
    {
        $pixKey = $pixKey !== null ? trim($pixKey) : null;
        $this->attributes['pix_key_encrypted'] = $pixKey === null || $pixKey === ''
            ? null
            : service('encrypter')->encrypt($pixKey);

        return $this;
    }

    public function getPixKey(): ?string
    {
        $encrypted = $this->attributes['pix_key_encrypted'] ?? null;
        if (!$encrypted) {
            return null;
        }

        try {
            return service('encrypter')->decrypt($encrypted);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function setProviderPayload($payload): self
    {
        if ($payload === null) {
            $this->attributes['provider_payload_encrypted'] = null;
            return $this;
        }

        $json = is_string($payload) ? $payload : json_encode($payload, JSON_UNESCAPED_UNICODE);
        $this->attributes['provider_payload_encrypted'] = service('encrypter')->encrypt((string) $json);
        return $this;
    }

    public function getProviderPayload(): ?array
    {
        $encrypted = $this->attributes['provider_payload_encrypted'] ?? null;
        if (!$encrypted) {
            return null;
        }

        try {
            $json = service('encrypter')->decrypt($encrypted);
            $decoded = json_decode((string) $json, true);
            return is_array($decoded) ? $decoded : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function isPending(): bool
    {
        return ($this->attributes['status'] ?? null) === 'pending';
    }

    public function isPaid(): bool
    {
        return ($this->attributes['status'] ?? null) === 'paid';
    }
}
