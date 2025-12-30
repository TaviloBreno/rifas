<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Raffle;

class RaffleModel extends Model
{
    protected $table            = 'raffles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Raffle::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'description',
        'image',
        'price',
        'quantity',
        'draw_date',
        'status',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name'        => 'required|min_length[3]|max_length[255]',
        'description' => 'permit_empty|max_length[5000]',
        'price'       => 'required|decimal|greater_than[0]',
        'quantity'    => 'required|integer|greater_than[0]',
        'draw_date'   => 'permit_empty|valid_date',
        'status'      => 'permit_empty|in_list[active,finished,cancelled]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'   => 'O nome da rifa é obrigatório.',
            'min_length' => 'O nome deve ter pelo menos 3 caracteres.',
            'max_length' => 'O nome não pode ter mais de 255 caracteres.',
        ],
        'description' => [
            'max_length' => 'A descrição não pode ter mais de 5000 caracteres.',
        ],
        'price' => [
            'required'     => 'O preço é obrigatório.',
            'decimal'      => 'O preço deve ser um valor decimal válido.',
            'greater_than' => 'O preço deve ser maior que zero.',
        ],
        'quantity' => [
            'required'     => 'A quantidade de números é obrigatória.',
            'integer'      => 'A quantidade deve ser um número inteiro.',
            'greater_than' => 'A quantidade deve ser maior que zero.',
        ],
        'draw_date' => [
            'valid_date' => 'A data do sorteio deve ser uma data válida.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Retorna apenas rifas ativas
     */
    public function getActive()
    {
        return $this->where('status', 'active')->findAll();
    }

    /**
     * Busca rifas por termo
     */
    public function search(string $term)
    {
        return $this->like('name', $term)
                    ->orLike('description', $term)
                    ->findAll();
    }
}
