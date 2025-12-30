<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<nav class="breadcrumb" aria-label="breadcrumbs">
    <ul>
        <li><a href="<?= site_url('admin/users') ?>">Usuários</a></li>
        <li class="is-active"><a href="#" aria-current="page"><?= esc($user->name) ?></a></li>
    </ul>
</nav>

<?php if (session()->getFlashdata('success')): ?>
    <div class="notification is-success is-light">
        <button class="delete" onclick="this.parentElement.remove()"></button>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="level">
    <div class="level-left">
        <div class="level-item">
            <h1 class="title"><?= esc($user->name) ?></h1>
        </div>
        <div class="level-item">
            <?php if ($user->is_active): ?>
                <span class="tag is-success is-medium">Ativo</span>
            <?php else: ?>
                <span class="tag is-danger is-medium">Inativo</span>
            <?php endif; ?>
        </div>
    </div>
    <div class="level-right">
        <div class="level-item">
            <div class="buttons">
                <a href="<?= site_url('admin/users/' . $user->id . '/edit') ?>" class="button is-warning">
                    <span class="icon"><i class="fas fa-edit"></i></span>
                    <span>Editar</span>
                </a>
                <?php if ($user->id != session()->get('user_id')): ?>
                    <form action="<?= site_url('admin/users/' . $user->id . '/toggle') ?>" method="post" style="display: inline;">
                        <?= csrf_field() ?>
                        <button type="submit" class="button <?= $user->is_active ? 'is-danger' : 'is-success' ?>">
                            <span class="icon"><i class="fas fa-<?= $user->is_active ? 'ban' : 'check' ?>"></i></span>
                            <span><?= $user->is_active ? 'Desativar' : 'Ativar' ?></span>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="columns">
    <div class="column is-4">
        <div class="box has-text-centered">
            <span class="icon is-large has-text-grey-light">
                <i class="fas fa-user-circle fa-5x"></i>
            </span>
            
            <h2 class="title is-4 mt-4"><?= esc($user->name) ?></h2>
            
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
            <span class="tag <?= $roleClass ?> is-medium"><?= $roleLabel ?></span>
            
            <hr>
            
            <table class="table is-fullwidth">
                <tbody>
                    <tr>
                        <th>Email:</th>
                        <td><a href="mailto:<?= esc($user->email) ?>"><?= esc($user->email) ?></a></td>
                    </tr>
                    <?php if ($user->phone): ?>
                    <tr>
                        <th>Telefone:</th>
                        <td>
                            <a href="https://wa.me/55<?= preg_replace('/\D/', '', $user->phone) ?>" target="_blank">
                                <?= esc($user->phone) ?>
                                <span class="icon is-small"><i class="fab fa-whatsapp"></i></span>
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($user->cpf): ?>
                    <tr>
                        <th>CPF:</th>
                        <td><?= esc($user->cpf) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Cadastro:</th>
                        <td><?= date('d/m/Y H:i', strtotime($user->created_at)) ?></td>
                    </tr>
                    <?php if ($user->updated_at): ?>
                    <tr>
                        <th>Atualizado:</th>
                        <td><?= date('d/m/Y H:i', strtotime($user->updated_at)) ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="column is-8">
        <!-- Estatísticas -->
        <div class="columns is-multiline">
            <div class="column is-4">
                <div class="box has-text-centered">
                    <p class="heading">Total de Pedidos</p>
                    <p class="title has-text-info"><?= $stats['total_orders'] ?? 0 ?></p>
                </div>
            </div>
            <div class="column is-4">
                <div class="box has-text-centered">
                    <p class="heading">Pedidos Pagos</p>
                    <p class="title has-text-success"><?= $stats['paid_orders'] ?? 0 ?></p>
                </div>
            </div>
            <div class="column is-4">
                <div class="box has-text-centered">
                    <p class="heading">Total Gasto</p>
                    <p class="title has-text-primary">R$ <?= number_format($stats['total_spent'] ?? 0, 2, ',', '.') ?></p>
                </div>
            </div>
        </div>
        
        <!-- Últimos Pedidos -->
        <div class="box">
            <h3 class="subtitle">
                <span class="icon"><i class="fas fa-shopping-cart"></i></span>
                Últimos Pedidos
            </h3>
            
            <?php if (empty($orders)): ?>
                <p class="has-text-grey has-text-centered">Nenhum pedido realizado.</p>
            <?php else: ?>
                <table class="table is-fullwidth is-striped">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Rifa</th>
                            <th>Qtd</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>
                                    <a href="<?= site_url('admin/orders/' . $order['id']) ?>">
                                        <?= esc($order['order_code']) ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?= site_url('admin/raffles/' . $order['raffle_id']) ?>">
                                        <?= esc($order['raffle_title'] ?? 'Rifa #' . $order['raffle_id']) ?>
                                    </a>
                                </td>
                                <td><?= $order['quantity'] ?></td>
                                <td>R$ <?= number_format($order['total'], 2, ',', '.') ?></td>
                                <td>
                                    <?php
                                    $orderStatusClass = match($order['status']) {
                                        'pending' => 'is-warning',
                                        'paid' => 'is-success',
                                        'cancelled' => 'is-danger',
                                        'expired' => 'is-dark',
                                        default => 'is-light'
                                    };
                                    $orderStatusLabel = match($order['status']) {
                                        'pending' => 'Pendente',
                                        'paid' => 'Pago',
                                        'cancelled' => 'Cancelado',
                                        'expired' => 'Expirado',
                                        default => $order['status']
                                    };
                                    ?>
                                    <span class="tag <?= $orderStatusClass ?>"><?= $orderStatusLabel ?></span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <a href="<?= site_url('admin/orders?user_id=' . $user->id) ?>" class="button is-small">Ver todos os pedidos</a>
            <?php endif; ?>
        </div>
        
        <!-- Números Ganhadores -->
        <?php if (!empty($wins)): ?>
            <div class="box">
                <h3 class="subtitle">
                    <span class="icon has-text-warning"><i class="fas fa-trophy"></i></span>
                    Prêmios Ganhos
                </h3>
                
                <table class="table is-fullwidth is-striped">
                    <thead>
                        <tr>
                            <th>Rifa</th>
                            <th>Número</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($wins as $win): ?>
                            <tr>
                                <td>
                                    <a href="<?= site_url('admin/raffles/' . $win['raffle_id']) ?>">
                                        <?= esc($win['raffle_title']) ?>
                                    </a>
                                </td>
                                <td>
                                    <span class="tag is-warning is-medium"><?= str_pad($win['winning_number'], 4, '0', STR_PAD_LEFT) ?></span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($win['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
