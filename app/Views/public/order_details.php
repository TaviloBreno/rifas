<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Pedido #<?= $order->id ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
    .order-hero {
        padding: 5rem 0 4rem;
        margin-top: -2rem;
        position: relative;
        text-align: center;
        color: white;
    }
    
    .order-hero.paid {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }
    
    .order-hero.pending {
        background: linear-gradient(135deg, #ffc107 0%, #ffca2c 100%);
    }
    
    .order-hero.pending .order-title,
    .order-hero.pending .order-subtitle {
        color: #333;
    }
    
    .order-hero.cancelled,
    .order-hero.expired {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    }
    
    .order-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .status-icon {
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        backdrop-filter: blur(10px);
    }
    
    .status-icon i {
        font-size: 3rem;
    }
    
    .order-hero.pending .status-icon i {
        color: #333;
    }
    
    .order-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }
    
    .order-subtitle {
        opacity: 0.9;
        font-size: 1.25rem;
    }
    
    .pix-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 2rem;
        background: white;
        color: #333;
        border-radius: 50px;
        font-weight: 700;
        margin-top: 1.5rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    }
    
    .pix-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        color: #333;
    }
    
    .order-main {
        margin-top: -3rem;
        position: relative;
        z-index: 10;
        padding-bottom: 3rem;
    }
    
    .order-card {
        background: white;
        border-radius: 25px;
        padding: 2.5rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    }
    
    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    .detail-section {
        background: #f8f9fa;
        border-radius: 20px;
        padding: 1.5rem;
    }
    
    .section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e0e0e0;
    }
    
    .section-header i {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .section-header h4 {
        font-weight: 700;
        color: #1a1a2e;
        margin: 0;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .detail-row:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        color: #6c757d;
        font-size: 0.875rem;
    }
    
    .detail-value {
        font-weight: 600;
        color: #1a1a2e;
    }
    
    .detail-value.highlight {
        font-size: 1.25rem;
        color: #28a745;
    }
    
    .status-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .status-tag.paid {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
    }
    
    .status-tag.pending {
        background: linear-gradient(135deg, #fff3cd, #ffeeba);
        color: #856404;
    }
    
    .status-tag.cancelled,
    .status-tag.expired {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        color: #721c24;
    }
    
    .numbers-section {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .numbers-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    
    .numbers-title i {
        color: #667eea;
        font-size: 1.25rem;
    }
    
    .numbers-title h4 {
        font-weight: 700;
        color: #1a1a2e;
        margin: 0;
    }
    
    .numbers-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    .number-badge {
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.125rem;
        transition: transform 0.2s ease;
    }
    
    .number-badge:hover {
        transform: scale(1.05);
    }
    
    .number-badge.paid {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }
    
    .number-badge.pending {
        background: linear-gradient(135deg, #ffc107, #ffca2c);
        color: #333;
    }
    
    .number-badge.default {
        background: #e9ecef;
        color: #6c757d;
    }
    
    .raffle-info-box {
        background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
        border-radius: 20px;
        padding: 1.5rem;
        border-left: 5px solid #17a2b8;
        margin-bottom: 2rem;
    }
    
    .raffle-info-box h4 {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        color: #0c5460;
        margin-bottom: 1rem;
    }
    
    .raffle-info-box p {
        color: #0c5460;
        margin: 0;
    }
    
    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .action-btn.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .action-btn.primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        color: white;
    }
    
    .action-btn.secondary {
        background: white;
        color: #1a1a2e;
        border: 2px solid #e0e0e0;
    }
    
    .action-btn.secondary:hover {
        border-color: #667eea;
        color: #667eea;
    }
    
    .action-btn.success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    
    .action-btn.success:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
        color: white;
    }
    
    @media (max-width: 768px) {
        .details-grid {
            grid-template-columns: 1fr;
        }
        
        .order-title {
            font-size: 1.5rem;
        }
    }
</style>

<?php
$statusClass = 'paid';
if ($order->isPending()) $statusClass = 'pending';
elseif ($order->isCancelled()) $statusClass = 'cancelled';
elseif (!$order->isPaid()) $statusClass = 'expired';
?>

<!-- Hero Section -->
<section class="order-hero <?= $statusClass ?>">
    <div class="container">
        <div class="status-icon">
            <?php if ($order->isPaid()): ?>
                <i class="fas fa-check"></i>
            <?php elseif ($order->isPending()): ?>
                <i class="fas fa-clock"></i>
            <?php elseif ($order->isCancelled()): ?>
                <i class="fas fa-times"></i>
            <?php else: ?>
                <i class="fas fa-hourglass-end"></i>
            <?php endif; ?>
        </div>
        
        <?php if ($order->isPaid()): ?>
            <h1 class="order-title">Pagamento Confirmado!</h1>
            <p class="order-subtitle">Seus números estão garantidos</p>
        <?php elseif ($order->isPending()): ?>
            <h1 class="order-title">Aguardando Pagamento</h1>
            <p class="order-subtitle">Complete o pagamento para garantir seus números</p>
            <a href="<?= site_url('pagamento/' . $order->id) ?>" class="pix-btn">
                <i class="fas fa-qrcode"></i> Ver PIX para Pagamento
            </a>
        <?php elseif ($order->isCancelled()): ?>
            <h1 class="order-title">Pedido Cancelado</h1>
            <p class="order-subtitle">Este pedido foi cancelado</p>
        <?php else: ?>
            <h1 class="order-title">Pedido Expirado</h1>
            <p class="order-subtitle">O tempo para pagamento expirou</p>
        <?php endif; ?>
        
        <p class="order-subtitle" style="margin-top: 1rem; opacity: 0.8;">
            Pedido #<?= str_pad($order->id, 6, '0', STR_PAD_LEFT) ?>
        </p>
    </div>
</section>

<!-- Main Content -->
<section class="section order-main">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-10 is-8-desktop">
                <div class="order-card">
                    
                    <!-- Details Grid -->
                    <div class="details-grid">
                        <div class="detail-section">
                            <div class="section-header">
                                <i class="fas fa-info-circle"></i>
                                <h4>Detalhes do Pedido</h4>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Rifa</span>
                                <span class="detail-value"><?= esc($order->raffle_name ?? $raffle->name ?? 'N/A') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Quantidade</span>
                                <span class="detail-value"><?= $order->total_numbers ?> número(s)</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Valor Total</span>
                                <span class="detail-value highlight"><?= $order->getFormattedAmount() ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Status</span>
                                <span class="status-tag <?= $statusClass ?>"><?= $order->getStatusLabel() ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Data do Pedido</span>
                                <span class="detail-value"><?= $order->getFormattedCreatedAt() ?></span>
                            </div>
                            <?php if ($order->isPaid()): ?>
                            <div class="detail-row">
                                <span class="detail-label">Data do Pagamento</span>
                                <span class="detail-value"><?= $order->getFormattedPaidAt() ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="detail-section">
                            <div class="section-header">
                                <i class="fas fa-user"></i>
                                <h4>Dados do Comprador</h4>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Nome</span>
                                <span class="detail-value"><?= esc($order->customer_name) ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">E-mail</span>
                                <span class="detail-value"><?= esc($order->customer_email) ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Telefone</span>
                                <span class="detail-value"><?= $order->getFormattedPhone() ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Numbers Section -->
                    <?php if (!empty($numbers)): ?>
                    <div class="numbers-section">
                        <div class="numbers-title">
                            <i class="fas fa-ticket-alt"></i>
                            <h4>Seus Números da Sorte</h4>
                        </div>
                        <div class="numbers-grid">
                            <?php 
                            $badgeClass = 'default';
                            if ($order->isPaid()) $badgeClass = 'paid';
                            elseif ($order->isPending()) $badgeClass = 'pending';
                            ?>
                            <?php foreach ($numbers as $number): ?>
                                <span class="number-badge <?= $badgeClass ?>">
                                    <?= $number->getFormattedNumber($raffle->getNumberDigits()) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($order->isPaid()): ?>
                    <!-- Raffle Info -->
                    <div class="raffle-info-box">
                        <h4><i class="fas fa-calendar-alt"></i> Informações do Sorteio</h4>
                        <p><strong>Data do Sorteio:</strong> <?= $raffle->getFormattedDrawDate() ?></p>
                        <p style="margin-top: 0.5rem;">
                            Acompanhe o resultado na página da rifa ou consulte seus pedidos pelo e-mail cadastrado.
                        </p>
                    </div>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <a href="<?= site_url('rifa/' . $raffle->slug) ?>" class="action-btn secondary">
                            <i class="fas fa-eye"></i> Ver Rifa
                        </a>
                        <a href="<?= site_url('meus-pedidos') ?>" class="action-btn secondary">
                            <i class="fas fa-list"></i> Meus Pedidos
                        </a>
                        <a href="<?= site_url('/') ?>" class="action-btn primary">
                            <i class="fas fa-home"></i> Início
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
