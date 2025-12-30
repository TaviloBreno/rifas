<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Entities\User;

class UserController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Lista todos os usuários
     */
    public function index()
    {
        $data = [
            'users' => $this->userModel->orderBy('created_at', 'DESC')->findAll(),
        ];

        return view('admin/users/index', $data);
    }

    /**
     * Formulário de novo usuário
     */
    public function new()
    {
        return view('admin/users/form', [
            'user' => null,
        ]);
    }

    /**
     * Cria um novo usuário
     */
    public function create()
    {
        $rules = [
            'name'     => 'required|min_length[3]|max_length[255]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'phone'    => 'permit_empty|max_length[20]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[cliente,admin,funcionario]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = new User([
            'name'      => $this->request->getPost('name'),
            'email'     => $this->request->getPost('email'),
            'phone'     => $this->request->getPost('phone'),
            'password'  => $this->request->getPost('password'),
            'role'      => $this->request->getPost('role'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        if ($this->userModel->insert($user)) {
            return redirect()->to('admin/users')->with('success', 'Usuário criado com sucesso!');
        }

        return redirect()->back()->withInput()->with('error', 'Erro ao criar usuário.');
    }

    /**
     * Exibe detalhes de um usuário
     */
    public function show($id = null)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'Usuário não encontrado.');
        }

        // Busca pedidos do usuário
        $orderModel = new \App\Models\OrderModel();
        
        $data = [
            'user'   => $user,
            'orders' => $orderModel->getByUser($id),
        ];

        return view('admin/users/show', $data);
    }

    /**
     * Formulário de edição
     */
    public function edit($id = null)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'Usuário não encontrado.');
        }

        return view('admin/users/form', [
            'user' => $user,
        ]);
    }

    /**
     * Atualiza um usuário
     */
    public function update($id = null)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'Usuário não encontrado.');
        }

        $rules = [
            'name'  => 'required|min_length[3]|max_length[255]',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'phone' => 'permit_empty|max_length[20]',
            'role'  => 'required|in_list[cliente,admin,funcionario]',
        ];

        // Senha é opcional na edição
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user->fill([
            'name'      => $this->request->getPost('name'),
            'email'     => $this->request->getPost('email'),
            'phone'     => $this->request->getPost('phone'),
            'role'      => $this->request->getPost('role'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        // Atualiza senha apenas se preenchida
        if ($this->request->getPost('password')) {
            $user->password = $this->request->getPost('password');
        }

        if ($this->userModel->save($user)) {
            return redirect()->to('admin/users/' . $id)->with('success', 'Usuário atualizado com sucesso!');
        }

        return redirect()->back()->withInput()->with('error', 'Erro ao atualizar usuário.');
    }

    /**
     * Desativa/ativa um usuário
     */
    public function toggleStatus($id = null)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'Usuário não encontrado.');
        }

        // Não pode desativar a si mesmo
        if ($user->id === session()->get('user_id')) {
            return redirect()->to('admin/users')->with('error', 'Você não pode desativar sua própria conta.');
        }

        $newStatus = $user->is_active ? 0 : 1;
        $this->userModel->update($id, ['is_active' => $newStatus]);

        $message = $newStatus ? 'Usuário ativado com sucesso!' : 'Usuário desativado com sucesso!';
        return redirect()->to('admin/users')->with('success', $message);
    }

    /**
     * Exclui um usuário
     */
    public function delete($id = null)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'Usuário não encontrado.');
        }

        // Não pode excluir a si mesmo
        if ($user->id === session()->get('user_id')) {
            return redirect()->to('admin/users')->with('error', 'Você não pode excluir sua própria conta.');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('admin/users')->with('success', 'Usuário excluído com sucesso!');
        }

        return redirect()->to('admin/users')->with('error', 'Erro ao excluir usuário.');
    }
}
