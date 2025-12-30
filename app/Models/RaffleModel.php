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
        'slug',
        'description',
        'prize_description',
        'image',
        'price',
        'quantity',
        'start_number',
        'min_per_purchase',
        'max_per_purchase',
        'numbers_generated',
        'winning_number',
        'user_id',
        'draw_date',
        'status',
        'is_featured',
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
     * Callbacks
     */
    protected $beforeInsert = ['generateSlug'];
    protected $beforeUpdate = ['generateSlug'];

    /**
     * Gera slug automaticamente
     */
    protected function generateSlug(array $data): array
    {
        if (isset($data['data']['name']) && empty($data['data']['slug'])) {
            $slug = url_title($data['data']['name'], '-', true);
            $slug = $this->makeUniqueSlug($slug, $data['data']['id'] ?? null);
            $data['data']['slug'] = $slug;
        }

        return $data;
    }

    /**
     * Garante que o slug seja único
     */
    protected function makeUniqueSlug(string $slug, ?int $excludeId = null): string
    {
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $builder = $this->where('slug', $slug);
            
            if ($excludeId) {
                $builder->where('id !=', $excludeId);
            }

            if ($builder->countAllResults() === 0) {
                break;
            }

            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Retorna apenas rifas ativas
     */
    public function getActive()
    {
        return $this->where('status', 'active')
                    ->orderBy('is_featured', 'DESC')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Retorna rifas em destaque
     */
    public function getFeatured(int $limit = 6)
    {
        return $this->where('status', 'active')
                    ->where('is_featured', 1)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Busca rifa por slug
     */
    public function findBySlug(string $slug)
    {
        return $this->where('slug', $slug)->first();
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
