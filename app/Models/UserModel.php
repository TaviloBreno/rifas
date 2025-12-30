<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\User;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = User::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'remember_token',
        'reset_token',
        'reset_expires',
        'is_active',
        'last_login',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name'     => 'required|min_length[3]|max_length[255]',
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'role'     => 'permit_empty|in_list[cliente,admin,funcionario]',
        'phone'    => 'permit_empty|max_length[20]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'   => 'O nome é obrigatório.',
            'min_length' => 'O nome deve ter pelo menos 3 caracteres.',
            'max_length' => 'O nome não pode ter mais de 255 caracteres.',
        ],
        'email' => [
            'required'    => 'O e-mail é obrigatório.',
            'valid_email' => 'Digite um e-mail válido.',
            'is_unique'   => 'Este e-mail já está cadastrado.',
        ],
        'password' => [
            'required'   => 'A senha é obrigatória.',
            'min_length' => 'A senha deve ter pelo menos 6 caracteres.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Busca usuário por email
     */
    public function findByEmail(string $email): ?User
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Busca usuário por token de lembrar
     */
    public function findByRememberToken(string $token): ?User
    {
        $hashedToken = hash('sha256', $token);
        return $this->where('remember_token', $hashedToken)->first();
    }

    /**
     * Busca usuário por token de reset
     */
    public function findByResetToken(string $token): ?User
    {
        $hashedToken = hash('sha256', $token);
        return $this->where('reset_token', $hashedToken)
                    ->where('reset_expires >', date('Y-m-d H:i:s'))
                    ->first();
    }

    /**
     * Atualiza último login
     */
    public function updateLastLogin(int $userId): bool
    {
        return $this->update($userId, ['last_login' => date('Y-m-d H:i:s')]);
    }

    /**
     * Busca usuários por role
     */
    public function getByRole(string $role)
    {
        return $this->where('role', $role)->findAll();
    }

    /**
     * Retorna usuários ativos
     */
    public function getActive()
    {
        return $this->where('is_active', 1)->findAll();
    }
}
