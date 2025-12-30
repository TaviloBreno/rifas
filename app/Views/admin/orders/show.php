<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<nav class="breadcrumb" aria-label="breadcrumbs">
    <ul>
        <li><a href="<?= site_url('admin/orders') ?>">Pedidos</a></li>
        <li class="is-active"><a href="#" aria-current="page"><?= esc($order['order_code']) ?></a></li>
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
            <h1 class="title">Pedido <?= esc($order['order_code']) ?></h1>
        </div>
        <div class="level-item">
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
            <span class="tag <?= $statusClass ?> is-medium"><?= $statusLabel ?></span>
        </div>
    </div>
    <div class="level-right">
        <div class="level-item">
            <?php if ($order['status'] === 'pending'): ?>
                <div class="buttons">
                    <button type="button" class="button is-success" onclick="confirmPayment()">
                        <span class="icon"><i class="fas fa-check"></i></span>
                        <span>Confirmar Pagamento</span>
                    </button>
                    <button type="button" class="button is-danger" onclick="confirmCancel()">
                        <span class="icon"><i class="fas fa-times"></i></span>
                        <span>Cancelar Pedido</span>
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="columns">
    <div class="column is-6">
        <div class="box">
            <h3 class="subtitle">
                <span class="icon"><i class="fas fa-user"></i></span>
                Dados do Cliente
            </h3>
            
            <table class="table is-fullwidth">
                <tbody>
                    <tr>
                        <th style="width: 40%;">Nome:</th>
                        <td><?= esc($order['customer_name']) ?></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><a href="mailto:<?= esc($order['customer_email']) ?>"><?= esc($order['customer_email']) ?></a></td>
                    </tr>
                    <tr>
                        <th>Telefone:</th>
                        <td>
                            <?php if ($order['customer_phone']): ?>
                                <a href="https://wa.me/55<?= preg_replace('/\D/', '', $order['customer_phone']) ?>" target="_blank">
                                    <?= esc($order['customer_phone']) ?>
                                    <span class="icon is-small"><i class="fab fa-whatsapp"></i></span>
                                </a>
                            <?php else: ?>
                                <span class="has-text-grey">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if ($order['customer_cpf']): ?>
                    <tr>
                        <th>CPF:</th>
                        <td><?= esc($order['customer_cpf']) ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="box">
            <h3 class="subtitle">
                <span class="icon"><i class="fas fa-ticket-alt"></i></span>
                Rifa
            </h3>
            
            <div class="media">
                <?php if ($raffle->image): ?>
                    <div class="media-left">
                        <figure class="image is-64x64">
                            <img src="<?= base_url('uploads/raffles/' . $raffle->image) ?>" alt="<?= esc($raffle->title) ?>" style="object-fit: cover; border-radius: 4px;">
                        </figure>
                    </div>
                <?php endif; ?>
                <div class="media-content">
                    <p class="title is-5">
                        <a href="<?= site_url('admin/raffles/' . $raffle->id) ?>"><?= esc($raffle->title) ?></a>
                    </p>
                    <p class="subtitle is-6">
                        R$ <?= number_format($raffle->price, 2, ',', '.') ?> por número
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="column is-6">
        <div class="box">
            <h3 class="subtitle">
                <span class="icon"><i class="fas fa-shopping-cart"></i></span>
                Detalhes do Pedido
            </h3>
            
            <table class="table is-fullwidth">
                <tbody>
                    <tr>
                        <th style="width: 40%;">Código:</th>
                        <td><strong><?= esc($order['order_code']) ?></strong></td>
                    </tr>
                    <tr>
                        <th>Quantidade:</th>
                        <td><?= $order['quantity'] ?> número(s)</td>
                    </tr>
                    <tr>
                        <th>Valor Total:</th>
                        <td><strong class="has-text-success is-size-5">R$ <?= number_format($order['total'], 2, ',', '.') ?></strong></td>
                    </tr>
                    <tr>
                        <th>Método de Pagamento:</th>
                        <td>
                            <?php
                            $methodLabel = match($order['payment_method'] ?? 'pix') {
                                'pix' => 'PIX',
                                'credit_card' => 'Cartão de Crédito',
                                'boleto' => 'Boleto',
                                default => $order['payment_method'] ?? '-'
                            };
                            ?>
                            <?= $methodLabel ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Criado em:</th>
                        <td><?= date('d/m/Y H:i:s', strtotime($order['created_at'])) ?></td>
                    </tr>
                    <?php if ($order['expires_at'] && $order['status'] === 'pending'): ?>
                    <tr>
                        <th>Expira em:</th>
                        <td>
                            <?php
                            $expiresAt = strtotime($order['expires_at']);
                            $now = time();
                            $isExpired = $expiresAt < $now;
                            ?>
                            <span class="<?= $isExpired ? 'has-text-danger' : 'has-text-warning' ?>">
                                <?= date('d/m/Y H:i:s', $expiresAt) ?>
                                <?php if ($isExpired): ?>
                                    <span class="tag is-danger is-light ml-2">Expirado</span>
                                <?php endif; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($order['paid_at']): ?>
                    <tr>
                        <th>Pago em:</th>
                        <td class="has-text-success"><?= date('d/m/Y H:i:s', strtotime($order['paid_at'])) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($order['transaction_id']): ?>
                    <tr>
                        <th>ID da Transação:</th>
                        <td><code><?= esc($order['transaction_id']) ?></code></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (!empty($order['notes'])): ?>
            <div class="box">
                <h3 class="subtitle">
                    <span class="icon"><i class="fas fa-sticky-note"></i></span>
                    Observações
                </h3>
                <div class="content">
                    <?= nl2br(esc($order['notes'])) ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Números do Pedido -->
<div class="box">
    <h3 class="subtitle">
        <span class="icon"><i class="fas fa-th"></i></span>
        Números Selecionados (<?= count($numbers) ?>)
    </h3>
    
    <div class="tags are-medium">
        <?php 
        $digits = strlen($raffle->total_numbers - 1 + $raffle->start_number);
        foreach ($numbers as $number): 
            $tagClass = match($number->status) {
                'available' => 'is-info',
                'reserved' => 'is-warning',
                'sold' => 'is-success',
                default => 'is-light'
            };
        ?>
            <span class="tag <?= $tagClass ?>">
                <?= str_pad($number->number, $digits, '0', STR_PAD_LEFT) ?>
            </span>
        <?php endforeach; ?>
    </div>
</div>

<!-- Histórico de Status -->
<?php if (!empty($statusHistory)): ?>
<div class="box">
    <h3 class="subtitle">
        <span class="icon"><i class="fas fa-history"></i></span>
        Histórico
    </h3>
    
    <div class="timeline">
        <?php foreach ($statusHistory as $history): ?>
            <div class="timeline-item">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <p class="heading"><?= date('d/m/Y H:i', strtotime($history['created_at'])) ?></p>
                    <p><?= esc($history['description']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Modal de Confirmação de Pagamento -->
<div class="modal" id="paymentModal">
    <div class="modal-background" onclick="closePaymentModal()"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Confirmar Pagamento</p>
            <button class="delete" aria-label="close" onclick="closePaymentModal()"></button>
        </header>
        <section class="modal-card-body">
            <p>Confirmar o pagamento do pedido <strong><?= esc($order['order_code']) ?></strong>?</p>
            <p class="has-text-success mt-2">Os <?= count($numbers) ?> número(s) serão marcados como vendidos.</p>
            
            <div class="field mt-4">
                <label class="label">ID da Transação (opcional)</label>
                <div class="control">
                    <input class="input" type="text" name="transaction_id" id="transactionId" placeholder="Identificador do pagamento">
                </div>
            </div>
        </section>
        <footer class="modal-card-foot">
            <form action="<?= site_url('admin/orders/' . $order['id'] . '/confirm') ?>" method="post" id="paymentForm">
                <?= csrf_field() ?>
                <input type="hidden" name="transaction_id" id="transactionIdHidden">
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
            <p>Tem certeza que deseja cancelar o pedido <strong><?= esc($order['order_code']) ?></strong>?</p>
            <p class="has-text-danger mt-2">Os <?= count($numbers) ?> número(s) reservados serão liberados para venda novamente.</p>
        </section>
        <footer class="modal-card-foot">
            <form action="<?= site_url('admin/orders/' . $order['id'] . '/cancel') ?>" method="post">
                <?= csrf_field() ?>
                <button type="submit" class="button is-danger">Cancelar Pedido</button>
            </form>
            <button class="button" onclick="closeCancelModal()">Voltar</button>
        </footer>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dbdbdb;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-marker {
    position: absolute;
    left: -26px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #3273dc;
    border: 2px solid #fff;
}

.timeline-content .heading {
    color: #7a7a7a;
    font-size: 0.75rem;
    margin-bottom: 0.25rem;
}
</style>

<script>
function confirmPayment() {
    document.getElementById('paymentModal').classList.add('is-active');
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.remove('is-active');
}

function confirmCancel() {
    document.getElementById('cancelModal').classList.add('is-active');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.remove('is-active');
}

document.getElementById('paymentForm').addEventListener('submit', function() {
    document.getElementById('transactionIdHidden').value = document.getElementById('transactionId').value;
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePaymentModal();
        closeCancelModal();
    }
});
</script>
<?= $this->endSection() ?>
