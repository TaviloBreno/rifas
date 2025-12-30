<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<h1 class="title">Pedidos</h1>

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
        <div class="columns is-multiline">
            <div class="column is-3">
                <div class="field">
                    <label class="label">Status</label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="status">
                                <option value="">Todos</option>
                                <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pendente</option>
                                <option value="paid" <?= ($filters['status'] ?? '') === 'paid' ? 'selected' : '' ?>>Pago</option>
                                <option value="cancelled" <?= ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelado</option>
                                <option value="expired" <?= ($filters['status'] ?? '') === 'expired' ? 'selected' : '' ?>>Expirado</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-3">
                <div class="field">
                    <label class="label">Rifa</label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="raffle_id">
                                <option value="">Todas</option>
                                <?php foreach ($raffles as $raffle): ?>
                                    <option value="<?= $raffle->id ?>" <?= ($filters['raffle_id'] ?? '') == $raffle->id ? 'selected' : '' ?>><?= esc($raffle->title) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-3">
                <div class="field">
                    <label class="label">Buscar</label>
                    <div class="control">
                        <input class="input" type="text" name="search" value="<?= esc($filters['search'] ?? '') ?>" placeholder="Nome, email ou código">
                    </div>
                </div>
            </div>
            <div class="column is-3">
                <div class="field">
                    <label class="label">&nbsp;</label>
                    <div class="control">
                        <button type="submit" class="button is-info">
                            <span class="icon"><i class="fas fa-search"></i></span>
                            <span>Filtrar</span>
                        </button>
                        <a href="<?= current_url() ?>" class="button">Limpar</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Ações em lote para pedidos expirados -->
<?php 
$pendingCount = 0;
foreach ($orders as $order) {
    if ($order['status'] === 'pending') $pendingCount++;
}
?>
<?php if ($pendingCount > 0): ?>
    <div class="notification is-warning is-light">
        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <span><strong><?= $pendingCount ?></strong> pedido(s) pendente(s). Pedidos expirados serão processados automaticamente.</span>
                </div>
            </div>
            <div class="level-right">
                <div class="level-item">
                    <form action="<?= site_url('admin/orders/process-expired') ?>" method="post" style="display: inline;">
                        <?= csrf_field() ?>
                        <button type="submit" class="button is-warning is-small">
                            <span class="icon"><i class="fas fa-clock"></i></span>
                            <span>Processar Expirados</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="box">
    <div class="table-container">
        <table class="table is-fullwidth is-striped is-hoverable">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Rifa</th>
                    <th>Cliente</th>
                    <th>Qtd</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th style="width: 180px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="8" class="has-text-centered has-text-grey">
                            Nenhum pedido encontrado.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>
                                <a href="<?= site_url('admin/orders/' . $order['id']) ?>">
                                    <strong><?= esc($order['order_code']) ?></strong>
                                </a>
                            </td>
                            <td>
                                <a href="<?= site_url('admin/raffles/' . $order['raffle_id']) ?>">
                                    <?= esc($order['raffle_title'] ?? 'Rifa #' . $order['raffle_id']) ?>
                                </a>
                            </td>
                            <td>
                                <div>
                                    <strong><?= esc($order['customer_name']) ?></strong><br>
                                    <small class="has-text-grey"><?= esc($order['customer_email']) ?></small>
                                </div>
                            </td>
                            <td><?= $order['quantity'] ?></td>
                            <td>R$ <?= number_format($order['total'], 2, ',', '.') ?></td>
                            <td>
                                <?php
                                $statusClass = match($order['status']) {
                                    'pending' => 'is-warning',
                                    'paid' => 'is-success',
                                    'cancelled' => 'is-danger',
                                    'expired' => 'is-dark',
                                    default => 'is-light'
                                };
                                $statusLabel = match($order['status']) {
                                    'pending' => 'Pendente',
                                    'paid' => 'Pago',
                                    'cancelled' => 'Cancelado',
                                    'expired' => 'Expirado',
                                    default => $order['status']
                                };
                                ?>
                                <span class="tag <?= $statusClass ?>"><?= $statusLabel ?></span>
                                <?php if ($order['status'] === 'pending' && !empty($order['expires_at'])): ?>
                                    <br><small class="has-text-grey">Expira: <?= date('d/m H:i', strtotime($order['expires_at'])) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                            </td>
                            <td>
                                <div class="buttons are-small">
                                    <a href="<?= site_url('admin/orders/' . $order['id']) ?>" class="button is-info is-outlined" title="Ver detalhes">
                                        <span class="icon"><i class="fas fa-eye"></i></span>
                                    </a>
                                    <?php if ($order['status'] === 'pending'): ?>
                                        <button type="button" class="button is-success is-outlined" title="Confirmar Pagamento" onclick="confirmPayment(<?= $order['id'] ?>, '<?= esc($order['order_code']) ?>')">
                                            <span class="icon"><i class="fas fa-check"></i></span>
                                        </button>
                                        <button type="button" class="button is-danger is-outlined" title="Cancelar" onclick="confirmCancel(<?= $order['id'] ?>, '<?= esc($order['order_code']) ?>')">
                                            <span class="icon"><i class="fas fa-times"></i></span>
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

<!-- Modal de Confirmação de Pagamento -->
<div class="modal" id="paymentModal">
    <div class="modal-background" onclick="closePaymentModal()"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Confirmar Pagamento</p>
            <button class="delete" aria-label="close" onclick="closePaymentModal()"></button>
        </header>
        <section class="modal-card-body">
            <p>Confirmar o pagamento do pedido <strong id="paymentOrderCode"></strong>?</p>
            <p class="has-text-success mt-2">Os números serão marcados como vendidos.</p>
        </section>
        <footer class="modal-card-foot">
            <form id="paymentForm" method="post">
                <?= csrf_field() ?>
                <button type="submit" class="button is-success">Confirmar Pagamento</button>
            </form>
            <button class="button" onclick="closePaymentModal()">Cancelar</button>
        </footer>
    </div>
</div>

<!-- Modal de Cancelamento -->
<div class="modal" id="cancelModal">
    <div class="modal-background" onclick="closeCancelModal()"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Cancelar Pedido</p>
            <button class="delete" aria-label="close" onclick="closeCancelModal()"></button>
        </header>
        <section class="modal-card-body">
            <p>Tem certeza que deseja cancelar o pedido <strong id="cancelOrderCode"></strong>?</p>
            <p class="has-text-danger mt-2">Os números reservados serão liberados.</p>
        </section>
        <footer class="modal-card-foot">
            <form id="cancelForm" method="post">
                <?= csrf_field() ?>
                <button type="submit" class="button is-danger">Cancelar Pedido</button>
            </form>
            <button class="button" onclick="closeCancelModal()">Voltar</button>
        </footer>
    </div>
</div>

<script>
function confirmPayment(id, code) {
    document.getElementById('paymentOrderCode').textContent = code;
    document.getElementById('paymentForm').action = '<?= site_url('admin/orders/') ?>' + id + '/confirm';
    document.getElementById('paymentModal').classList.add('is-active');
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.remove('is-active');
}

function confirmCancel(id, code) {
    document.getElementById('cancelOrderCode').textContent = code;
    document.getElementById('cancelForm').action = '<?= site_url('admin/orders/') ?>' + id + '/cancel';
    document.getElementById('cancelModal').classList.add('is-active');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.remove('is-active');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePaymentModal();
        closeCancelModal();
    }
});
</script>
<?= $this->endSection() ?>
