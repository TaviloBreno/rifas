<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>
<?= $this->section('page-title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Estatísticas -->
<div class="columns">
    <div class="column">
        <div class="stat-card">
            <div class="is-flex is-justify-content-space-between is-align-items-center">
                <div>
                    <p class="heading">Total de Rifas</p>
                    <p class="title"><?= $totalRaffles ?></p>
                    <p class="is-size-7 has-text-success"><?= $activeRaffles ?> ativas</p>
                </div>
                <div class="stat-icon has-background-primary-light has-text-primary">
                    <i class="fas fa-ticket-alt"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="column">
        <div class="stat-card">
            <div class="is-flex is-justify-content-space-between is-align-items-center">
                <div>
                    <p class="heading">Total de Pedidos</p>
                    <p class="title"><?= $totalOrders ?></p>
                    <p class="is-size-7 has-text-warning"><?= $pendingOrders ?> pendentes</p>
                </div>
                <div class="stat-icon has-background-info-light has-text-info">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="column">
        <div class="stat-card">
            <div class="is-flex is-justify-content-space-between is-align-items-center">
                <div>
                    <p class="heading">Vendas Confirmadas</p>
                    <p class="title"><?= $paidOrders ?></p>
                    <p class="is-size-7 has-text-success">R$ <?= number_format($totalSales, 2, ',', '.') ?></p>
                </div>
                <div class="stat-icon has-background-success-light has-text-success">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="column">
        <div class="stat-card">
            <div class="is-flex is-justify-content-space-between is-align-items-center">
                <div>
                    <p class="heading">Usuários</p>
                    <p class="title"><?= $totalUsers ?></p>
                    <p class="is-size-7 has-text-grey">cadastrados</p>
                </div>
                <div class="stat-icon has-background-warning-light has-text-warning">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ações Rápidas -->
<div class="box mt-5">
    <h3 class="title is-5"><i class="fas fa-bolt"></i> Ações Rápidas</h3>
    <div class="buttons">
        <a href="<?= site_url('admin/raffles/new') ?>" class="button is-primary">
            <i class="fas fa-plus mr-1"></i> Nova Rifa
        </a>
        <a href="<?= site_url('admin/orders?status=pending') ?>" class="button is-warning">
            <i class="fas fa-clock mr-1"></i> Pedidos Pendentes (<?= $pendingOrders ?>)
        </a>
        <a href="<?= site_url('admin/orders/process-expired') ?>" class="button is-dark">
            <i class="fas fa-sync mr-1"></i> Processar Expirados
        </a>
    </div>
</div>

<div class="columns mt-5">
    <!-- Últimos Pedidos -->
    <div class="column is-8">
        <div class="box">
            <h3 class="title is-5"><i class="fas fa-list"></i> Últimos Pedidos</h3>
            
            <?php if (empty($recentOrders)): ?>
                <p class="has-text-grey">Nenhum pedido encontrado.</p>
            <?php else: ?>
                <table class="table is-fullwidth is-striped is-hoverable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Rifa</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($recentOrders, 0, 5) as $order): ?>
                            <tr>
                                <td>
                                    <a href="<?= site_url('admin/orders/' . $order->id) ?>">
                                        #<?= str_pad($order->id, 6, '0', STR_PAD_LEFT) ?>
                                    </a>
                                </td>
                                <td><?= esc($order->customer_name) ?></td>
                                <td><?= esc($order->raffle_name ?? '-') ?></td>
                                <td><?= $order->getFormattedAmount() ?></td>
                                <td>
                                    <span class="tag <?= $order->getStatusClass() ?>">
                                        <?= $order->getStatusLabel() ?>
                                    </span>
                                </td>
                                <td><?= $order->getFormattedCreatedAt() ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="<?= site_url('admin/orders') ?>" class="button is-small is-info is-outlined">
                    Ver todos os pedidos
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Últimos Ganhadores -->
    <div class="column is-4">
        <div class="box">
            <h3 class="title is-5"><i class="fas fa-trophy has-text-warning"></i> Últimos Ganhadores</h3>
            
            <?php if (empty($recentWinners)): ?>
                <p class="has-text-grey">Nenhum ganhador ainda.</p>
            <?php else: ?>
                <?php foreach ($recentWinners as $winner): ?>
                    <div class="media">
                        <div class="media-left">
                            <span class="tag is-warning is-medium">
                                #<?= $winner->getFormattedNumber() ?>
                            </span>
                        </div>
                        <div class="media-content">
                            <p class="has-text-weight-bold"><?= esc($winner->winner_name) ?></p>
                            <p class="is-size-7 has-text-grey"><?= esc($winner->raffle_name ?? 'Rifa') ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
