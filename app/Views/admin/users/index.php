<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="level">
    <div class="level-left">
        <div class="level-item">
            <h1 class="title">Usuários</h1>
        </div>
    </div>
    <div class="level-right">
        <div class="level-item">
            <a href="<?= site_url('admin/users/create') ?>" class="button is-primary">
                <span class="icon"><i class="fas fa-plus"></i></span>
                <span>Novo Usuário</span>
            </a>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="notification is-success is-light">
        <button class="delete" onclick="this.parentElement.remove()"></button>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="notification is-danger is-light">
        <button class="delete" onclick="this.parentElement.remove()"></button>
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<!-- Filtros -->
<div class="box">
    <form method="get" action="<?= current_url() ?>">
        <div class="columns">
            <div class="column is-3">
                <div class="field">
                    <label class="label">Perfil</label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="role">
                                <option value="">Todos</option>
                                <option value="admin" <?= ($filters['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrador</option>
                                <option value="operator" <?= ($filters['role'] ?? '') === 'operator' ? 'selected' : '' ?>>Operador</option>
                                <option value="customer" <?= ($filters['role'] ?? '') === 'customer' ? 'selected' : '' ?>>Cliente</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-3">
                <div class="field">
                    <label class="label">Status</label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="status">
                                <option value="">Todos</option>
                                <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Ativo</option>
                                <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inativo</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    <label class="label">Buscar</label>
                    <div class="control">
                        <input class="input" type="text" name="search" value="<?= esc($filters['search'] ?? '') ?>" placeholder="Nome ou email">
                    </div>
                </div>
            </div>
            <div class="column is-2">
                <div class="field">
                    <label class="label">&nbsp;</label>
                    <div class="control">
                        <button type="submit" class="button is-info is-fullwidth">
                            <span class="icon"><i class="fas fa-search"></i></span>
                            <span>Filtrar</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="box">
    <div class="table-container">
        <table class="table is-fullwidth is-striped is-hoverable">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Perfil</th>
                    <th>Status</th>
                    <th>Cadastro</th>
                    <th style="width: 180px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="7" class="has-text-centered has-text-grey">
                            Nenhum usuário encontrado.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user->id ?></td>
                            <td>
                                <div class="is-flex is-align-items-center">
                                    <span class="icon is-medium has-text-grey mr-2">
                                        <i class="fas fa-user-circle fa-2x"></i>
                                    </span>
                                    <strong><?= esc($user->name) ?></strong>
                                </div>
                            </td>
                            <td>
                                <a href="mailto:<?= esc($user->email) ?>"><?= esc($user->email) ?></a>
                            </td>
                            <td>
                                <?php
                                $roleClass = match($user->role) {
                                    'admin' => 'is-danger',
                                    'operator' => 'is-warning',
                                    'customer' => 'is-info',
                                    default => 'is-light'
                                };
                                $roleLabel = match($user->role) {
                                    'admin' => 'Administrador',
                                    'operator' => 'Operador',
                                    'customer' => 'Cliente',
                                    default => $user->role
                                };
                                ?>
                                <span class="tag <?= $roleClass ?>"><?= $roleLabel ?></span>
                            </td>
                            <td>
                                <?php if ($user->is_active): ?>
                                    <span class="tag is-success">Ativo</span>
                                <?php else: ?>
                                    <span class="tag is-danger">Inativo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= date('d/m/Y', strtotime($user->created_at)) ?>
                            </td>
                            <td>
                                <div class="buttons are-small">
                                    <a href="<?= site_url('admin/users/' . $user->id) ?>" class="button is-info is-outlined" title="Ver detalhes">
                                        <span class="icon"><i class="fas fa-eye"></i></span>
                                    </a>
                                    <a href="<?= site_url('admin/users/' . $user->id . '/edit') ?>" class="button is-warning is-outlined" title="Editar">
                                        <span class="icon"><i class="fas fa-edit"></i></span>
                                    </a>
                                    <?php if ($user->id != session()->get('user_id')): ?>
                                        <button type="button" class="button <?= $user->is_active ? 'is-danger' : 'is-success' ?> is-outlined" title="<?= $user->is_active ? 'Desativar' : 'Ativar' ?>" onclick="confirmToggle(<?= $user->id ?>, '<?= esc($user->name) ?>', <?= $user->is_active ? 'true' : 'false' ?>)">
                                            <span class="icon"><i class="fas fa-<?= $user->is_active ? 'ban' : 'check' ?>"></i></span>
                                        </button>
                                        <button type="button" class="button is-danger is-outlined" title="Excluir" onclick="confirmDelete(<?= $user->id ?>, '<?= esc($user->name) ?>')">
                                            <span class="icon"><i class="fas fa-trash"></i></span>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if (isset($pager)): ?>
        <nav class="pagination is-centered" role="navigation" aria-label="pagination">
            <?= $pager->links() ?>
        </nav>
    <?php endif; ?>
</div>

<!-- Modal de Alternar Status -->
<div class="modal" id="toggleModal">
    <div class="modal-background" onclick="closeToggleModal()"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title" id="toggleTitle">Alterar Status</p>
            <button class="delete" aria-label="close" onclick="closeToggleModal()"></button>
        </header>
        <section class="modal-card-body">
            <p id="toggleMessage"></p>
        </section>
        <footer class="modal-card-foot">
            <form id="toggleForm" method="post">
                <?= csrf_field() ?>
                <button type="submit" class="button" id="toggleButton">Confirmar</button>
            </form>
            <button class="button" onclick="closeToggleModal()">Cancelar</button>
        </footer>
    </div>
</div>

<!-- Modal de Exclusão -->
<div class="modal" id="deleteModal">
    <div class="modal-background" onclick="closeDeleteModal()"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Confirmar Exclusão</p>
            <button class="delete" aria-label="close" onclick="closeDeleteModal()"></button>
        </header>
        <section class="modal-card-body">
            <p>Tem certeza que deseja excluir o usuário "<strong id="deleteName"></strong>"?</p>
            <p class="has-text-danger mt-2">Esta ação não pode ser desfeita!</p>
        </section>
        <footer class="modal-card-foot">
            <form id="deleteForm" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="button is-danger">Excluir</button>
            </form>
            <button class="button" onclick="closeDeleteModal()">Cancelar</button>
        </footer>
    </div>
</div>

<script>
function confirmToggle(id, name, isActive) {
    if (isActive) {
        document.getElementById('toggleTitle').textContent = 'Desativar Usuário';
        document.getElementById('toggleMessage').innerHTML = 'Deseja desativar o usuário "<strong>' + name + '</strong>"? O usuário não conseguirá mais acessar o sistema.';
        document.getElementById('toggleButton').className = 'button is-danger';
        document.getElementById('toggleButton').textContent = 'Desativar';
    } else {
        document.getElementById('toggleTitle').textContent = 'Ativar Usuário';
        document.getElementById('toggleMessage').innerHTML = 'Deseja ativar o usuário "<strong>' + name + '</strong>"?';
        document.getElementById('toggleButton').className = 'button is-success';
        document.getElementById('toggleButton').textContent = 'Ativar';
    }
    document.getElementById('toggleForm').action = '<?= site_url('admin/users/') ?>' + id + '/toggle';
    document.getElementById('toggleModal').classList.add('is-active');
}

function closeToggleModal() {
    document.getElementById('toggleModal').classList.remove('is-active');
}

function confirmDelete(id, name) {
    document.getElementById('deleteName').textContent = name;
    document.getElementById('deleteForm').action = '<?= site_url('admin/users/') ?>' + id;
    document.getElementById('deleteModal').classList.add('is-active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('is-active');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeToggleModal();
        closeDeleteModal();
    }
});
</script>
<?= $this->endSection() ?>
