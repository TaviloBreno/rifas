<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Entities\User;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper('cookie');
    }

    /**
     * Exibe o formulário de login
     */
    public function login()
    {
        // Verifica se já está logado
        if (session()->get('user_id')) {
            return redirect()->to('/');
        }

        // Verifica cookie de "lembrar-me"
        $rememberToken = get_cookie('remember_token');
        if ($rememberToken) {
            $user = $this->userModel->findByRememberToken($rememberToken);
            if ($user && $user->isActive()) {
                $this->setUserSession($user);
                return redirect()->to('/');
            }
        }

        return view('auth/login');
    }

    /**
     * Processa o login
     */
    public function attemptLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        $messages = [
            'email' => [
                'required'    => 'O e-mail é obrigatório.',
                'valid_email' => 'Digite um e-mail válido.',
            ],
            'password' => [
                'required' => 'A senha é obrigatória.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');

        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'E-mail ou senha incorretos.');
        }

        if (!$user->verifyPassword($password)) {
            return redirect()->back()->withInput()->with('error', 'E-mail ou senha incorretos.');
        }

        if (!$user->isActive()) {
            return redirect()->back()->withInput()->with('error', 'Sua conta está desativada. Entre em contato com o suporte.');
        }

        // Define a sessão do usuário
        $this->setUserSession($user);

        // Atualiza último login
        $this->userModel->updateLastLogin($user->id);

        // Se marcou "lembrar-me", cria o cookie
        if ($remember) {
            $token = $user->generateRememberToken();
            $this->userModel->save($user);
            
            set_cookie('remember_token', $token, 60 * 60 * 24 * 30); // 30 dias
        }

        return redirect()->to('/')->with('success', 'Bem-vindo(a), ' . $user->getFirstName() . '!');
    }

    /**
     * Exibe o formulário de registro
     */
    public function register()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/');
        }

        return view('auth/register');
    }

    /**
     * Processa o registro
     */
    public function attemptRegister()
    {
        $rules = [
            'name'             => 'required|min_length[3]|max_length[255]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'phone'            => 'permit_empty|max_length[20]',
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        $messages = [
            'name' => [
                'required'   => 'O nome é obrigatório.',
                'min_length' => 'O nome deve ter pelo menos 3 caracteres.',
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
            'password_confirm' => [
                'required' => 'Confirme sua senha.',
                'matches'  => 'As senhas não conferem.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = new User([
            'name'      => $this->request->getPost('name'),
            'email'     => $this->request->getPost('email'),
            'phone'     => $this->request->getPost('phone'),
            'password'  => $this->request->getPost('password'),
            'role'      => 'cliente', // Novos usuários são sempre clientes
            'is_active' => 1,
        ]);

        if ($this->userModel->save($user)) {
            return redirect()->to('login')->with('success', 'Conta criada com sucesso! Faça login para continuar.');
        }

        return redirect()->back()->withInput()->with('error', 'Erro ao criar conta. Tente novamente.');
    }

    /**
     * Exibe o formulário de esqueci a senha
     */
    public function forgotPassword()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/');
        }

        return view('auth/forgot_password');
    }

    /**
     * Processa o pedido de recuperação de senha
     */
    public function attemptForgotPassword()
    {
        $rules = [
            'email' => 'required|valid_email',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $user  = $this->userModel->findByEmail($email);

        // Sempre exibe mensagem de sucesso para não revelar se o e-mail existe
        $successMessage = 'Se o e-mail estiver cadastrado, você receberá um link para redefinir sua senha.';

        if (!$user) {
            return redirect()->back()->with('success', $successMessage);
        }

        // Gera o token de reset
        $token = $user->generateResetToken();
        $this->userModel->save($user);

        // Aqui você enviaria o e-mail com o link de reset
        // Por enquanto, vamos apenas mostrar o link (em produção, isso seria removido)
        $resetLink = site_url('reset-password/' . $token);

        // Em desenvolvimento, mostra o link
        if (ENVIRONMENT === 'development') {
            return redirect()->back()->with('success', "Link de recuperação (apenas dev): <a href='{$resetLink}' class='has-text-link'>{$resetLink}</a>");
        }

        // TODO: Enviar e-mail com o link de reset
        // $this->sendResetEmail($user, $resetLink);

        return redirect()->back()->with('success', $successMessage);
    }

    /**
     * Exibe o formulário de reset de senha
     */
    public function resetPassword($token = null)
    {
        if (!$token) {
            return redirect()->to('forgot-password')->with('error', 'Token inválido.');
        }

        $user = $this->userModel->findByResetToken($token);

        if (!$user) {
            return redirect()->to('forgot-password')->with('error', 'Token inválido ou expirado.');
        }

        return view('auth/reset_password', ['token' => $token]);
    }

    /**
     * Processa o reset de senha
     */
    public function attemptResetPassword()
    {
        $rules = [
            'token'            => 'required',
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        $messages = [
            'password' => [
                'required'   => 'A nova senha é obrigatória.',
                'min_length' => 'A senha deve ter pelo menos 6 caracteres.',
            ],
            'password_confirm' => [
                'required' => 'Confirme sua nova senha.',
                'matches'  => 'As senhas não conferem.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $token = $this->request->getPost('token');
        $user  = $this->userModel->findByResetToken($token);

        if (!$user) {
            return redirect()->to('forgot-password')->with('error', 'Token inválido ou expirado.');
        }

        // Atualiza a senha
        $user->setPassword($this->request->getPost('password'));
        $user->clearResetToken();

        if ($this->userModel->save($user)) {
            return redirect()->to('login')->with('success', 'Senha alterada com sucesso! Faça login com sua nova senha.');
        }

        return redirect()->back()->with('error', 'Erro ao alterar senha. Tente novamente.');
    }

    /**
     * Logout
     */
    public function logout()
    {
        // Remove o cookie de lembrar-me
        if (get_cookie('remember_token')) {
            $user = $this->userModel->find(session()->get('user_id'));
            if ($user) {
                $user->remember_token = null;
                $this->userModel->save($user);
            }
            delete_cookie('remember_token');
        }

        // Destrói a sessão
        session()->destroy();

        return redirect()->to('login')->with('success', 'Você saiu do sistema.');
    }

    /**
     * Define os dados do usuário na sessão
     */
    private function setUserSession(User $user): void
    {
        session()->set([
            'user_id'    => $user->id,
            'user_name'  => $user->name,
            'user_email' => $user->email,
            'user_role'  => $user->role,
            'logged_in'  => true,
        ]);
    }
}
