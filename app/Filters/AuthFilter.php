<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Verifica se o usuário está autenticado
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('login')->with('error', 'Você precisa fazer login para acessar esta página.');
        }

        // Se há argumentos (roles permitidas), verifica
        if ($arguments) {
            $userRole = session()->get('user_role');
            
            if (!in_array($userRole, $arguments)) {
                return redirect()->to('/')->with('error', 'Você não tem permissão para acessar esta página.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Não faz nada após a requisição
    }
}
