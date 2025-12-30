<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Raffle extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at', 'draw_date'];
    protected $casts   = [
        'id'       => 'integer',
        'price'    => 'float',
        'quantity' => 'integer',
    ];

    /**
     * Retorna o preço formatado em Real
     */
    public function getFormattedPrice(): string
    {
        return 'R$ ' . number_format($this->attributes['price'], 2, ',', '.');
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

        return $labels[$this->attributes['status']] ?? 'Desconhecido';
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

        return $classes[$this->attributes['status']] ?? 'is-warning';
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
        return $this->attributes['status'] === 'active';
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
        return $this->attributes['price'] * $this->attributes['quantity'];
    }

    /**
     * Retorna o valor total formatado
     */
    public function getFormattedTotalValue(): string
    {
        return 'R$ ' . number_format($this->getTotalValue(), 2, ',', '.');
    }
}
