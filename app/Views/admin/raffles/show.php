<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<nav class="breadcrumb" aria-label="breadcrumbs">
    <ul>
        <li><a href="<?= site_url('admin/raffles') ?>">Rifas</a></li>
        <li class="is-active"><a href="#" aria-current="page"><?= esc($raffle->title) ?></a></li>
    </ul>
</nav>

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

<div class="level">
    <div class="level-left">
        <div class="level-item">
            <h1 class="title"><?= esc($raffle->title) ?></h1>
        </div>
        <div class="level-item">
            <?php
            $statusClass = match($raffle->status) {
                'active' => 'is-success',
                'pending' => 'is-warning',
                'finished' => 'is-info',
                'cancelled' => 'is-danger',
                default => 'is-light'
            };
            $statusLabel = match($raffle->status) {
                'active' => 'Ativa',
                'pending' => 'Pendente',
                'finished' => 'Finalizada',
                'cancelled' => 'Cancelada',
                default => $raffle->status
            };
            ?>
            <span class="tag <?= $statusClass ?> is-medium"><?= $statusLabel ?></span>
        </div>
    </div>
    <div class="level-right">
        <div class="level-item">
            <div class="buttons">
                <a href="<?= site_url('admin/raffles/' . $raffle->id . '/edit') ?>" class="button is-warning">
                    <span class="icon"><i class="fas fa-edit"></i></span>
                    <span>Editar</span>
                </a>
                <?php if ($raffle->status === 'active' && !$raffle->winning_number): ?>
                    <button type="button" class="button is-success" onclick="confirmDraw()">
                        <span class="icon"><i class="fas fa-trophy"></i></span>
                        <span>Realizar Sorteio</span>
                    </button>
                <?php endif; ?>
                <?php if (empty($numbers)): ?>
                    <button type="button" class="button is-info" onclick="confirmGenerateNumbers()">
                        <span class="icon"><i class="fas fa-th"></i></span>
                        <span>Gerar Números</span>
                    </button>
                <?php endif; ?>
                <a href="<?= site_url('rifa/' . $raffle->slug) ?>" class="button is-link" target="_blank">
                    <span class="icon"><i class="fas fa-external-link-alt"></i></span>
                    <span>Ver Página</span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="columns">
    <div class="column is-4">
        <div class="box">
            <?php if ($raffle->image): ?>
                <figure class="image is-4by3 mb-4">
                    <img src="<?= base_url('uploads/raffles/' . $raffle->image) ?>" alt="<?= esc($raffle->title) ?>" style="object-fit: cover; border-radius: 4px;">
                </figure>
            <?php endif; ?>
            
            <h3 class="subtitle">Informações</h3>
            
            <table class="table is-fullwidth">
                <tbody>
                    <tr>
                        <th>Preço:</th>
                        <td>R$ <?= number_format($raffle->price, 2, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <th>Total de Números:</th>
                        <td><?= number_format($raffle->total_numbers, 0, '', '.') ?></td>
                    </tr>
                    <tr>
                        <th>Início:</th>
                        <td><?= $raffle->start_number ?></td>
                    </tr>
                    <tr>
                        <th>Mín/Máx Compra:</th>
                        <td><?= $raffle->min_per_purchase ?? 1 ?> / <?= $raffle->max_per_purchase ?? '∞' ?></td>
                    </tr>
                    <tr>
                        <th>Data Início:</th>
                        <td><?= $raffle->start_date ? date('d/m/Y H:i', strtotime($raffle->start_date)) : '-' ?></td>
                    </tr>
                    <tr>
                        <th>Data Fim:</th>
                        <td><?= $raffle->end_date ? date('d/m/Y H:i', strtotime($raffle->end_date)) : '-' ?></td>
                    </tr>
                    <tr>
                        <th>Data Sorteio:</th>
                        <td><?= $raffle->draw_date ? date('d/m/Y H:i', strtotime($raffle->draw_date)) : '-' ?></td>
                    </tr>
                    <tr>
                        <th>Destaque:</th>
                        <td><?= $raffle->is_featured ? '<span class="tag is-warning">Sim</span>' : '<span class="tag">Não</span>' ?></td>
                    </tr>
                    <tr>
                        <th>Criada em:</th>
                        <td><?= date('d/m/Y H:i', strtotime($raffle->created_at)) ?></td>
                    </tr>
                </tbody>
            </table>
            
            <?php if ($raffle->description): ?>
                <h4 class="subtitle is-6 mt-4">Descrição</h4>
                <div class="content">
                    <?= nl2br(esc($raffle->description)) ?>
                </div>
            <?php endif; ?>
            
            <?php if ($raffle->prize_description): ?>
                <h4 class="subtitle is-6 mt-4">Prêmio</h4>
                <div class="content">
                    <?= nl2br(esc($raffle->prize_description)) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="column is-8">
        <!-- Estatísticas -->
        <div class="columns is-multiline mb-4">
            <div class="column is-4">
                <div class="box has-text-centered">
                    <p class="heading">Disponíveis</p>
                    <p class="title has-text-info"><?= number_format($stats['available'] ?? 0, 0, '', '.') ?></p>
                </div>
            </div>
            <div class="column is-4">
                <div class="box has-text-centered">
                    <p class="heading">Reservados</p>
                    <p class="title has-text-warning"><?= number_format($stats['reserved'] ?? 0, 0, '', '.') ?></p>
                </div>
            </div>
            <div class="column is-4">
                <div class="box has-text-centered">
                    <p class="heading">Vendidos</p>
                    <p class="title has-text-success"><?= number_format($stats['sold'] ?? 0, 0, '', '.') ?></p>
                </div>
            </div>
        </div>
        
        <?php if ($raffle->winning_number !== null): ?>
            <div class="box has-background-success-light">
                <div class="has-text-centered">
                    <h3 class="subtitle">Número Sorteado</h3>
                    <p class="title is-1 has-text-success"><?= str_pad($raffle->winning_number, strlen($raffle->total_numbers - 1), '0', STR_PAD_LEFT) ?></p>
                    <?php if (isset($winner)): ?>
                        <p class="mt-3">
                            <strong>Ganhador:</strong> <?= esc($winner['customer_name']) ?><br>
                            <small><?= esc($winner['customer_email']) ?></small>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Grade de Números -->
        <div class="box">
            <h3 class="subtitle">
                Números
                <span class="is-pulled-right">
                    <span class="tag is-info is-light">Disponível</span>
                    <span class="tag is-warning is-light">Reservado</span>
                    <span class="tag is-success is-light">Vendido</span>
                </span>
            </h3>
            
            <?php if (empty($numbers)): ?>
                <div class="notification is-warning is-light">
                    <p>Os números ainda não foram gerados para esta rifa.</p>
                    <button type="button" class="button is-info mt-3" onclick="confirmGenerateNumbers()">
                        <span class="icon"><i class="fas fa-th"></i></span>
                        <span>Gerar Números Agora</span>
                    </button>
                </div>
            <?php else: ?>
                <div class="numbers-grid">
                    <?php 
                    $digits = strlen($raffle->total_numbers - 1 + $raffle->start_number);
                    foreach ($numbers as $number): 
                        $statusClass = match($number->status) {
                            'available' => 'available',
                            'reserved' => 'reserved',
                            'sold' => 'sold',
                            default => ''
                        };
                    ?>
                        <div class="number-cell <?= $statusClass ?> <?= $number->number == $raffle->winning_number ? 'winner' : '' ?>" title="<?= ucfirst($number->status) ?>">
                            <?= str_pad($number->number, $digits, '0', STR_PAD_LEFT) ?>
                            <?php if ($number->number == $raffle->winning_number): ?>
                                <i class="fas fa-trophy"></i>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Últimos Pedidos -->
        <?php if (!empty($recentOrders)): ?>
            <div class="box">
                <h3 class="subtitle">Últimos Pedidos</h3>
                <table class="table is-fullwidth is-striped">
                    <thead>
                        <tr>
                            <th>Pedido</th>
                            <th>Cliente</th>
                            <th>Números</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td>
                                    <a href="<?= site_url('admin/orders/' . $order['id']) ?>">#<?= $order['id'] ?></a>
                                </td>
                                <td><?= esc($order['customer_name']) ?></td>
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
                                <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="<?= site_url('admin/orders?raffle_id=' . $raffle->id) ?>" class="button is-small">Ver todos os pedidos</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de Gerar Números -->
<div class="modal" id="generateModal">
    <div class="modal-background" onclick="closeGenerateModal()"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Gerar Números</p>
            <button class="delete" aria-label="close" onclick="closeGenerateModal()"></button>
        </header>
        <section class="modal-card-body">
            <p>Deseja gerar os <?= number_format($raffle->total_numbers, 0, '', '.') ?> números para esta rifa?</p>
            <p class="has-text-info mt-2">Os números serão criados a partir do número <?= $raffle->start_number ?>.</p>
        </section>
        <footer class="modal-card-foot">
            <form action="<?= site_url('admin/raffles/' . $raffle->id . '/generate-numbers') ?>" method="post">
                <?= csrf_field() ?>
                <button type="submit" class="button is-info">Gerar Números</button>
            </form>
            <button class="button" onclick="closeGenerateModal()">Cancelar</button>
        </footer>
    </div>
</div>

<!-- Modal de Sorteio -->
<div class="modal" id="drawModal">
    <div class="modal-background" onclick="closeDrawModal()"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Realizar Sorteio</p>
            <button class="delete" aria-label="close" onclick="closeDrawModal()"></button>
        </header>
        <section class="modal-card-body">
            <p>Tem certeza que deseja realizar o sorteio desta rifa?</p>
            <p class="has-text-warning mt-2"><strong>Atenção:</strong> O sorteio será realizado entre os <?= number_format($stats['sold'] ?? 0, 0, '', '.') ?> números vendidos e a rifa será finalizada.</p>
        </section>
        <footer class="modal-card-foot">
            <form action="<?= site_url('admin/raffles/' . $raffle->id . '/draw') ?>" method="post">
                <?= csrf_field() ?>
                <button type="submit" class="button is-success">Realizar Sorteio</button>
            </form>
            <button class="button" onclick="closeDrawModal()">Cancelar</button>
        </footer>
    </div>
</div>

<style>
.numbers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
    gap: 6px;
    max-height: 400px;
    overflow-y: auto;
    padding: 10px;
    background: #f5f5f5;
    border-radius: 4px;
}

.number-cell {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 8px 4px;
    font-size: 0.85rem;
    font-weight: 600;
    border-radius: 4px;
    cursor: default;
    position: relative;
}

.number-cell.available {
    background: #e3f2fd;
    color: #1565c0;
    border: 1px solid #90caf9;
}

.number-cell.reserved {
    background: #fff3e0;
    color: #e65100;
    border: 1px solid #ffcc80;
}

.number-cell.sold {
    background: #e8f5e9;
    color: #2e7d32;
    border: 1px solid #a5d6a7;
}

.number-cell.winner {
    background: #ffd700;
    color: #000;
    border: 2px solid #ff9800;
    animation: pulse 2s infinite;
}

.number-cell.winner .fa-trophy {
    position: absolute;
    top: -5px;
    right: -5px;
    font-size: 10px;
    color: #ff9800;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}
</style>

<script>
function confirmGenerateNumbers() {
    document.getElementById('generateModal').classList.add('is-active');
}

function closeGenerateModal() {
    document.getElementById('generateModal').classList.remove('is-active');
}

function confirmDraw() {
    document.getElementById('drawModal').classList.add('is-active');
}

function closeDrawModal() {
    document.getElementById('drawModal').classList.remove('is-active');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeGenerateModal();
        closeDrawModal();
    }
});
</script>
<?= $this->endSection() ?>
