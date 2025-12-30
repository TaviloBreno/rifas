<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Prize extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'claimed_at'];
    protected $casts   = [
        'id'            => 'integer',
        'raffle_id'     => 'integer',
        'position'      => 'integer',
        'value'         => 'float',
        'winner_number' => '?integer',
    ];

    /**
     * Retorna a URL da imagem do prêmio
     */
    public function getImageUrl(): string
    {
        if ($this->image) {
            return base_url('uploads/prizes/' . $this->image);
        }

        // Imagem padrão do Unsplash
        $images = [
            'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400',
            'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400',
            'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=400',
            'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=400',
            'https://images.unsplash.com/photo-1560343090-f0409e92791a?w=400',
        ];

        return $images[$this->id % count($images)];
    }

    /**
     * Retorna o valor formatado
     */
    public function getFormattedValue(): string
    {
        if ($this->value) {
            return 'R$ ' . number_format($this->value, 2, ',', '.');
        }
        return 'N/A';
    }

    /**
     * Retorna o label da posição
     */
    public function getPositionLabel(): string
    {
        $labels = [
            1 => '1º Lugar',
            2 => '2º Lugar',
            3 => '3º Lugar',
            4 => '4º Lugar',
            5 => '5º Lugar',
        ];

        return $labels[$this->position] ?? $this->position . 'º Lugar';
    }

    /**
     * Retorna a classe CSS baseada na posição
     */
    public function getPositionClass(): string
    {
        $classes = [
            1 => 'is-warning',   // Ouro
            2 => 'is-light',     // Prata
            3 => 'is-danger',    // Bronze
        ];

        return $classes[$this->position] ?? 'is-info';
    }

    /**
     * Verifica se o prêmio foi sorteado
     */
    public function isDrawn(): bool
    {
        return $this->winner_number !== null;
    }

    /**
     * Verifica se o prêmio foi reivindicado
     */
    public function isClaimed(): bool
    {
        return $this->claimed_at !== null;
    }

    /**
     * Retorna a data de reivindicação formatada
     */
    public function getFormattedClaimedAt(): string
    {
        if ($this->claimed_at) {
            return $this->claimed_at->format('d/m/Y H:i');
        }
        return '';
    }

    /**
     * Retorna descrição curta
     */
    public function getShortDescription(int $length = 100): string
    {
        if (!$this->description) {
            return '';
        }

        if (strlen($this->description) <= $length) {
            return $this->description;
        }

        return substr($this->description, 0, $length) . '...';
    }
}
