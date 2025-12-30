<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<nav class="breadcrumb" aria-label="breadcrumbs">
    <ul>
        <li><a href="<?= site_url('admin/users') ?>">Usuários</a></li>
        <li class="is-active"><a href="#" aria-current="page"><?= isset($user) ? 'Editar' : 'Novo Usuário' ?></a></li>
    </ul>
</nav>

<h1 class="title"><?= isset($user) ? 'Editar Usuário' : 'Novo Usuário' ?></h1>

<?php if (session()->getFlashdata('error')): ?>
    <div class="notification is-danger is-light">
        <button class="delete" onclick="this.parentElement.remove()"></button>
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if (isset($errors) && !empty($errors)): ?>
    <div class="notification is-danger is-light">
        <button class="delete" onclick="this.parentElement.remove()"></button>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= isset($user) ? site_url('admin/users/' . $user->id) : site_url('admin/users') ?>" method="post">
    <?= csrf_field() ?>
    <?php if (isset($user)): ?>
        <input type="hidden" name="_method" value="PUT">
    <?php endif; ?>
    
    <div class="box">
        <h2 class="subtitle">Informações Básicas</h2>
        
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    <label class="label">Nome Completo *</label>
                    <div class="control has-icons-left">
                        <input class="input" type="text" name="name" value="<?= old('name', $user->name ?? '') ?>" required placeholder="Nome do usuário">
                        <span class="icon is-small is-left">
                            <i class="fas fa-user"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    <label class="label">Email *</label>
                    <div class="control has-icons-left">
                        <input class="input" type="email" name="email" value="<?= old('email', $user->email ?? '') ?>" required placeholder="email@exemplo.com">
                        <span class="icon is-small is-left">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    <label class="label">Perfil *</label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="role" required>
                                <option value="">Selecione...</option>
                                <option value="admin" <?= old('role', $user->role ?? '') === 'admin' ? 'selected' : '' ?>>Administrador</option>
                                <option value="operator" <?= old('role', $user->role ?? '') === 'operator' ? 'selected' : '' ?>>Operador</option>
                                <option value="customer" <?= old('role', $user->role ?? 'customer') === 'customer' ? 'selected' : '' ?>>Cliente</option>
                            </select>
                        </div>
                    </div>
                    <p class="help">
                        <strong>Administrador:</strong> Acesso total ao sistema<br>
                        <strong>Operador:</strong> Pode gerenciar rifas e pedidos<br>
                        <strong>Cliente:</strong> Apenas compra rifas
                    </p>
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    <label class="label">Status</label>
                    <div class="control">
                        <label class="checkbox">
                            <input type="checkbox" name="is_active" value="1" <?= old('is_active', $user->is_active ?? true) ? 'checked' : '' ?>>
                            Usuário ativo
                        </label>
                    </div>
                    <p class="help">Usuários inativos não conseguem acessar o sistema.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="box">
        <h2 class="subtitle">Senha</h2>
        
        <?php if (isset($user)): ?>
            <div class="notification is-info is-light">
                <p>Deixe os campos de senha em branco para manter a senha atual.</p>
            </div>
        <?php endif; ?>
        
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    <label class="label">Senha <?= isset($user) ? '' : '*' ?></label>
                    <div class="control has-icons-left">
                        <input class="input" type="password" name="password" id="password" <?= isset($user) ? '' : 'required' ?> minlength="6" placeholder="Mínimo 6 caracteres">
                        <span class="icon is-small is-left">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    <label class="label">Confirmar Senha <?= isset($user) ? '' : '*' ?></label>
                    <div class="control has-icons-left">
                        <input class="input" type="password" name="password_confirm" id="password_confirm" <?= isset($user) ? '' : 'required' ?> placeholder="Repita a senha">
                        <span class="icon is-small is-left">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="box">
        <h2 class="subtitle">Informações Adicionais</h2>
        
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    <label class="label">Telefone</label>
                    <div class="control has-icons-left">
                        <input class="input" type="tel" name="phone" value="<?= old('phone', $user->phone ?? '') ?>" placeholder="(00) 00000-0000">
                        <span class="icon is-small is-left">
                            <i class="fas fa-phone"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    <label class="label">CPF</label>
                    <div class="control has-icons-left">
                        <input class="input" type="text" name="cpf" value="<?= old('cpf', $user->cpf ?? '') ?>" placeholder="000.000.000-00">
                        <span class="icon is-small is-left">
                            <i class="fas fa-id-card"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="field is-grouped">
        <div class="control">
            <button type="submit" class="button is-primary is-medium">
                <span class="icon"><i class="fas fa-save"></i></span>
                <span><?= isset($user) ? 'Salvar Alterações' : 'Criar Usuário' ?></span>
            </button>
        </div>
        <div class="control">
            <a href="<?= site_url('admin/users') ?>" class="button is-medium">Cancelar</a>
        </div>
    </div>
</form>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('password_confirm').value;
    
    if (password && password !== confirm) {
        e.preventDefault();
        alert('As senhas não coincidem!');
    }
});
</script>
<?= $this->endSection() ?>
