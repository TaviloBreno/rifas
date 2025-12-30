<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class User extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at', 'last_login', 'reset_expires'];
    protected $casts   = [
        'id'        => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Criptografa a senha automaticamente ao definir
     */
    public function setPassword(string $password): self
    {
        $this->attributes['password'] = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    /**
     * Verifica se a senha está correta
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->attributes['password']);
    }

    /**
     * Retorna o primeiro nome do usuário
     */
    public function getFirstName(): string
    {
        $names = explode(' ', $this->attributes['name']);
        return $names[0];
    }

    /**
     * Retorna o role traduzido
     */
    public function getRoleLabel(): string
    {
        $labels = [
            'cliente'     => 'Cliente',
            'admin'       => 'Administrador',
            'funcionario' => 'Funcionário',
        ];

        return $labels[$this->attributes['role']] ?? 'Desconhecido';
    }

    /**
     * Retorna a classe CSS do role
     */
    public function getRoleClass(): string
    {
        $classes = [
            'cliente'     => 'is-info',
            'admin'       => 'is-danger',
            'funcionario' => 'is-warning',
        ];

        return $classes[$this->attributes['role']] ?? 'is-light';
    }

    /**
     * Retorna a URL do avatar ou um avatar padrão
     */
    public function getAvatarUrl(): string
    {
        if (!empty($this->attributes['avatar']) && file_exists(FCPATH . 'uploads/avatars/' . $this->attributes['avatar'])) {
            return base_url('uploads/avatars/' . $this->attributes['avatar']);
        }

        // Avatar com iniciais
        $initials = strtoupper(substr($this->attributes['name'], 0, 2));
        return "https://ui-avatars.com/api/?name={$initials}&background=667eea&color=fff&size=128";
    }

    /**
     * Verifica se o usuário é admin
     */
    public function isAdmin(): bool
    {
        return $this->attributes['role'] === 'admin';
    }

    /**
     * Verifica se o usuário é funcionário
     */
    public function isFuncionario(): bool
    {
        return $this->attributes['role'] === 'funcionario';
    }

    /**
     * Verifica se o usuário é cliente
     */
    public function isCliente(): bool
    {
        return $this->attributes['role'] === 'cliente';
    }

    /**
     * Verifica se o usuário está ativo
     */
    public function isActive(): bool
    {
        return (bool) $this->attributes['is_active'];
    }

    /**
     * Gera um token de "lembrar-me"
     */
    public function generateRememberToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $this->attributes['remember_token'] = hash('sha256', $token);
        return $token;
    }

    /**
     * Gera um token de reset de senha
     */
    public function generateResetToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $this->attributes['reset_token'] = hash('sha256', $token);
        $this->attributes['reset_expires'] = date('Y-m-d H:i:s', strtotime('+1 hour'));
        return $token;
    }

    /**
     * Verifica se o token de reset é válido
     */
    public function isResetTokenValid(string $token): bool
    {
        if (empty($this->attributes['reset_token']) || empty($this->attributes['reset_expires'])) {
            return false;
        }

        if (strtotime($this->attributes['reset_expires']) < time()) {
            return false;
        }

        return hash('sha256', $token) === $this->attributes['reset_token'];
    }

    /**
     * Limpa o token de reset
     */
    public function clearResetToken(): void
    {
        $this->attributes['reset_token'] = null;
        $this->attributes['reset_expires'] = null;
    }
}
