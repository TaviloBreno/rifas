<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Setting extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at'];
    protected $casts   = [
        'id' => 'integer',
    ];

    /**
     * Retorna o valor convertido para o tipo correto
     */
    public function getTypedValue()
    {
        $value = $this->attributes['value'];
        
        switch ($this->attributes['type']) {
            case 'integer':
                return (int) $value;
            case 'boolean':
                return (bool) $value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * Retorna o label do tipo
     */
    public function getTypeLabel(): string
    {
        $labels = [
            'string'  => 'Texto',
            'integer' => 'Número',
            'boolean' => 'Sim/Não',
            'json'    => 'JSON',
            'text'    => 'Texto Longo',
        ];

        return $labels[$this->attributes['type']] ?? 'Desconhecido';
    }

    /**
     * Retorna o label do grupo
     */
    public function getGroupLabel(): string
    {
        $labels = [
            'general' => 'Geral',
            'payment' => 'Pagamento',
            'raffle'  => 'Rifas',
            'email'   => 'E-mail',
        ];

        return $labels[$this->attributes['group']] ?? 'Outro';
    }
}
