<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Raffle extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at', 'draw_date'];
    protected $casts   = [
        'id'                => 'integer',
        'price'             => 'float',
        'quantity'          => 'integer',
        'start_number'      => 'integer',
        'min_per_purchase'  => 'integer',
        'max_per_purchase'  => 'integer',
        'numbers_generated' => 'boolean',
        'winning_number'    => '?integer',
        'user_id'           => '?integer',
        'is_featured'       => 'boolean',
    ];

    /**
     * Retorna o preço formatado em Real
     */
    public function getFormattedPrice(): string
    {
        return 'R$ ' . number_format($this->attributes['price'] ?? 0, 2, ',', '.');
    }

    /**
     * Retorna a data do sorteio formatada
     */
    public function getFormattedDrawDate(): string
    {
        if (empty($this->attributes['draw_date'])) {
            return 'Não definida';
        }
        
        return date('d/m/Y', strtotime($this->attributes['draw_date']));
    }

    /**
     * Retorna o status traduzido
     */
    public function getStatusLabel(): string
    {
        $labels = [
            'active'    => 'Ativa',
            'finished'  => 'Finalizada',
            'cancelled' => 'Cancelada',
        ];

        return $labels[$this->attributes['status'] ?? ''] ?? 'Desconhecido';
    }

    /**
     * Retorna a classe CSS do status
     */
    public function getStatusClass(): string
    {
        $classes = [
            'active'    => 'is-success',
            'finished'  => 'is-info',
            'cancelled' => 'is-danger',
        ];

        return $classes[$this->attributes['status'] ?? ''] ?? 'is-warning';
    }

    /**
     * Retorna a URL da imagem ou uma imagem padrão
     */
    public function getImageUrl(): string
    {
        if (!empty($this->attributes['image']) && file_exists(FCPATH . 'uploads/' . $this->attributes['image'])) {
            return base_url('uploads/' . $this->attributes['image']);
        }

        return 'https://via.placeholder.com/400x300?text=Sem+Imagem';
    }

    /**
     * Verifica se a rifa está ativa
     */
    public function isActive(): bool
    {
        return ($this->attributes['status'] ?? '') === 'active';
    }

    /**
     * Verifica se a rifa está finalizada
     */
    public function isFinished(): bool
    {
        return ($this->attributes['status'] ?? '') === 'finished';
    }

    /**
     * Verifica se os números foram gerados
     */
    public function hasNumbersGenerated(): bool
    {
        return (bool) ($this->attributes['numbers_generated'] ?? false);
    }

    /**
     * Verifica se é destaque
     */
    public function isFeatured(): bool
    {
        return (bool) ($this->attributes['is_featured'] ?? false);
    }

    /**
     * Retorna a descrição resumida
     */
    public function getShortDescription(int $length = 100): string
    {
        if (empty($this->attributes['description'])) {
            return '';
        }

        if (strlen($this->attributes['description']) <= $length) {
            return $this->attributes['description'];
        }

        return substr($this->attributes['description'], 0, $length) . '...';
    }

    /**
     * Calcula o valor total da rifa
     */
    public function getTotalValue(): float
    {
        return ($this->attributes['price'] ?? 0) * ($this->attributes['quantity'] ?? 0);
    }

    /**
     * Retorna o valor total formatado
     */
    public function getFormattedTotalValue(): string
    {
        return 'R$ ' . number_format($this->getTotalValue(), 2, ',', '.');
    }

    /**
     * Retorna o último número disponível
     */
    public function getLastNumber(): int
    {
        return ($this->attributes['start_number'] ?? 0) + ($this->attributes['quantity'] ?? 0) - 1;
    }

    /**
     * Retorna a URL pública da rifa
     */
    public function getPublicUrl(): string
    {
        return base_url('rifa/' . ($this->attributes['slug'] ?? $this->attributes['id']));
    }

    /**
     * Calcula quantos dígitos os números devem ter
     */
    public function getNumberDigits(): int
    {
        $lastNumber = $this->getLastNumber();
        return max(2, strlen((string) $lastNumber));
    }

    /**
     * Retorna o número vencedor formatado
     */
    public function getFormattedWinningNumber(): string
    {
        if (empty($this->attributes['winning_number'])) {
            return '-';
        }

        return str_pad($this->attributes['winning_number'], $this->getNumberDigits(), '0', STR_PAD_LEFT);
    }
}