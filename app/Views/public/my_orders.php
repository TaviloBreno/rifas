<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Meus Pedidos<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
    .orders-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 6rem 0 4rem;
        margin-top: -2rem;
        position: relative;
        text-align: center;
        color: white;
    }
    
    .orders-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .orders-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        backdrop-filter: blur(10px);
    }
    
    .orders-icon i {
        font-size: 2.5rem;
    }
    
    .orders-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }
    
    .orders-subtitle {
        opacity: 0.9;
    }
    
    .orders-main {
        margin-top: -3rem;
        position: relative;
        z-index: 10;
        padding-bottom: 3rem;
    }
    
    .search-card {
        background: white;
        border-radius: 25px;
        padding: 2rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        max-width: 600px;
        margin: 0 auto 3rem;
    }
    
    .search-form {
        display: flex;
        gap: 0.75rem;
    }
    
    .search-input-wrapper {
        flex: 1;
        position: relative;
    }
    
    .search-input-wrapper i {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    
    .search-input {
        width: 100%;
        padding: 1.25rem 1.25rem 1.25rem 3.5rem;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }
    
    .search-btn {
        padding: 1.25rem 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }
    
    .search-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    }
    
    .results-title {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .results-title h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a2e;
    }
    
    .results-title span {
        color: #667eea;
    }
    
    .empty-results {
        background: white;
        border-radius: 20px;
        padding: 3rem;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    }
    
    .empty-results i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
    
    .empty-results h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 0.5rem;
    }
    
    .empty-results p {
        color: #6c757d;
    }
    
    .order-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
    }
    
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .order-id {
        font-size: 0.75rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .order-number {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1a1a2e;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .status-badge.paid {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
    }
    
    .status-badge.pending {
        background: linear-gradient(135deg, #fff3cd, #ffeeba);
        color: #856404;
    }
    
    .status-badge.cancelled,
    .status-badge.expired {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        color: #721c24;
    }
    
    .order-info {
        margin-bottom: 1.5rem;
    }
    
    .order-info-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0;
        color: #495057;
    }
    
    .order-info-row i {
        width: 20px;
        color: #667eea;
    }
    
    .order-info-row .value {
        font-weight: 600;
        color: #1a1a2e;
    }
    
    .order-info-row .value.price {
        color: #28a745;
        font-size: 1.125rem;
    }
    
    .order-actions {
        display: flex;
        gap: 0.75rem;
    }
    
    .order-btn {
        flex: 1;
        padding: 0.75rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .order-btn.details {
        background: #f8f9fa;
        color: #495057;
        border: 2px solid #e0e0e0;
    }
    
    .order-btn.details:hover {
        border-color: #667eea;
        color: #667eea;
    }
    
    .order-btn.pay {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    
    .order-btn.pay:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(40, 167, 69, 0.3);
        color: white;
    }
    
    @media (max-width: 768px) {
        .orders-title {
            font-size: 1.5rem;
        }
        
        .search-form {
            flex-direction: column;
        }
        
        .order-actions {
            flex-direction: column;
        }
    }
</style>

<!-- Hero Section -->
<section class="orders-hero">
    <div class="container">
        <div class="orders-icon">
            <i class="fas fa-receipt"></i>
        </div>
        <h1 class="orders-title">Meus Pedidos</h1>
        <p class="orders-subtitle">Consulte seus pedidos utilizando o e-mail cadastrado</p>
    </div>
</section>

<!-- Main Content -->
<section class="section orders-main">
    <div class="container">
        
        <!-- Search Card -->
        <div class="search-card">
            <form action="<?= site_url('buscar-pedidos') ?>" method="post" class="search-form">
                <?= csrf_field() ?>
                <div class="search-input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input class="search-input" type="email" name="email" 
                           placeholder="Digite seu e-mail" 
                           value="<?= esc($email ?? '') ?>" required>
                </div>
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </form>
        </div>

        <?php if (isset($orders)): ?>
            <?php if (empty($orders)): ?>
                <div class="empty-results">
                    <i class="fas fa-inbox"></i>
                    <h3>Nenhum pedido encontrado</h3>
                    <p>Não encontramos pedidos associados a este e-mail.</p>
                </div>
            <?php else: ?>
                <div class="results-title">
                    <h2>Encontrados <span><?= count($orders) ?></span> pedido(s)</h2>
                </div>
                
                <div class="columns is-multiline">
                    <?php foreach ($orders as $order): ?>
                        <?php
                        $statusClass = 'paid';
                        if ($order->isPending()) $statusClass = 'pending';
                        elseif ($order->isCancelled()) $statusClass = 'cancelled';
                        elseif (!$order->isPaid()) $statusClass = 'expired';
                        ?>
                        <div class="column is-6">
                            <div class="order-card">
                                <div class="order-header">
                                    <div>
                                        <div class="order-id">Pedido</div>
                                        <div class="order-number">#<?= str_pad($order->id, 6, '0', STR_PAD_LEFT) ?></div>
                                    </div>
                                    <div class="status-badge <?= $statusClass ?>">
                                        <i class="fas <?= $order->getStatusIcon() ?>"></i>
                                        <?= $order->getStatusLabel() ?>
                                    </div>
                                </div>
                                
                                <div class="order-info">
                                    <div class="order-info-row">
                                        <i class="fas fa-ticket-alt"></i>
                                        <span>Rifa:</span>
                                        <span class="value"><?= esc($order->raffle_name ?? 'Rifa #' . $order->raffle_id) ?></span>
                                    </div>
                                    <div class="order-info-row">
                                        <i class="fas fa-hashtag"></i>
                                        <span>Números:</span>
                                        <span class="value"><?= $order->total_numbers ?> número(s)</span>
                                    </div>
                                    <div class="order-info-row">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span>Valor:</span>
                                        <span class="value price"><?= $order->getFormattedAmount() ?></span>
                                    </div>
                                    <div class="order-info-row">
                                        <i class="fas fa-calendar"></i>
                                        <span>Data:</span>
                                        <span class="value"><?= $order->getFormattedCreatedAt() ?></span>
                                    </div>
                                </div>
                                
                                <div class="order-actions">
                                    <a href="<?= site_url('pedido/' . $order->id) ?>" class="order-btn details">
                                        <i class="fas fa-eye"></i> Ver Detalhes
                                    </a>
                                    <?php if ($order->isPending()): ?>
                                        <a href="<?= site_url('pagamento/' . $order->id) ?>" class="order-btn pay">
                                            <i class="fas fa-qrcode"></i> Pagar
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>
