<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= esc($raffle->name) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
// Fallback images do Unsplash para rifas
$fallbackImages = [
    'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&q=80',
    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&q=80',
    'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=800&q=80',
    'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80',
    'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=800&q=80',
];
$raffleImage = $raffle->image ? base_url('uploads/raffles/' . $raffle->image) : $fallbackImages[($raffle->id ?? 0) % count($fallbackImages)];
?>

<style>
    .raffle-hero {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.95), rgba(118, 75, 162, 0.95)),
                    url('<?= $raffleImage ?>') center/cover;
        padding: 6rem 0 4rem;
        margin-top: -2rem;
        position: relative;
        overflow: hidden;
    }
    
    .raffle-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .breadcrumb-modern {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50px;
        padding: 0.5rem 1.5rem;
        display: inline-block;
        backdrop-filter: blur(10px);
    }
    
    .breadcrumb-modern a {
        color: rgba(255, 255, 255, 0.8) !important;
        transition: all 0.3s ease;
    }
    
    .breadcrumb-modern a:hover {
        color: #FFD700 !important;
    }
    
    .breadcrumb-modern li.is-active a {
        color: #fff !important;
        font-weight: 600;
    }
    
    .breadcrumb-modern li + li::before {
        color: rgba(255, 255, 255, 0.5) !important;
    }
    
    .product-showcase {
        margin-top: -8rem;
        position: relative;
        z-index: 10;
    }
    
    .product-image-card {
        background: white;
        border-radius: 20px;
        padding: 1rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        position: relative;
        overflow: hidden;
    }
    
    .product-image-card img {
        border-radius: 15px;
        width: 100%;
        height: 400px;
        object-fit: cover;
    }
    
    .status-badge-floating {
        position: absolute;
        top: 2rem;
        right: 2rem;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.75rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        z-index: 5;
    }
    
    .status-badge-floating.active {
        background: linear-gradient(135deg, #00d1b2, #00c4a7);
        color: white;
        animation: pulse-glow 2s infinite;
    }
    
    .status-badge-floating.finished {
        background: linear-gradient(135deg, #ff6b6b, #ee5a5a);
        color: white;
    }
    
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 5px 20px rgba(0, 209, 178, 0.4); }
        50% { box-shadow: 0 5px 30px rgba(0, 209, 178, 0.8); }
    }
    
    .winner-showcase {
        background: linear-gradient(135deg, #FFD700, #FFA500);
        border-radius: 15px;
        padding: 1.5rem;
        margin-top: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    
    .winner-showcase::before {
        content: '🏆';
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 4rem;
        opacity: 0.3;
    }
    
    .winner-showcase h4 {
        color: #333;
        margin-bottom: 1rem;
    }
    
    .winner-item {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .winner-number {
        background: #333;
        color: #FFD700;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 1.25rem;
    }
    
    .info-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        height: 100%;
    }
    
    .raffle-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1a1a2e;
        line-height: 1.2;
        margin-bottom: 1rem;
    }
    
    .prize-highlight {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin: 1.5rem 0;
        position: relative;
        overflow: hidden;
    }
    
    .prize-highlight::before {
        content: '🎁';
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 3rem;
        opacity: 0.3;
    }
    
    .prize-highlight strong {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.9;
    }
    
    .prize-highlight p {
        font-size: 1.25rem;
        font-weight: 600;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin: 1.5rem 0;
    }
    
    .stat-item {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        padding: 1.25rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .stat-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .stat-item.price {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    }
    
    .stat-item.date {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
    }
    
    .stat-item .label {
        font-size: 0.75rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
    }
    
    .stat-item .value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a2e;
    }
    
    .stat-item.price .value {
        color: #28a745;
    }
    
    .progress-section {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1.5rem;
        margin: 1.5rem 0;
    }
    
    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .progress-stats {
        display: flex;
        gap: 1.5rem;
    }
    
    .progress-stat {
        text-align: center;
    }
    
    .progress-stat .num {
        font-size: 1.5rem;
        font-weight: 700;
    }
    
    .progress-stat .lbl {
        font-size: 0.75rem;
        color: #6c757d;
        text-transform: uppercase;
    }
    
    .progress-stat.available .num { color: #28a745; }
    .progress-stat.sold .num { color: #dc3545; }
    
    .progress-bar-modern {
        height: 12px;
        background: #e9ecef;
        border-radius: 50px;
        overflow: hidden;
        position: relative;
    }
    
    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #28a745, #20c997);
        border-radius: 50px;
        transition: width 0.5s ease;
        position: relative;
    }
    
    .progress-bar-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 2s infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    .progress-percentage {
        text-align: center;
        margin-top: 0.75rem;
        font-weight: 600;
        color: #28a745;
    }
    
    .buy-button {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        color: white;
        padding: 1.25rem 2rem;
        border-radius: 15px;
        font-size: 1.25rem;
        font-weight: 700;
        width: 100%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
        text-decoration: none;
    }
    
    .buy-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(40, 167, 69, 0.4);
        color: white;
    }
    
    .buy-button.disabled {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        cursor: not-allowed;
        box-shadow: none;
    }
    
    .buy-limits {
        text-align: center;
        margin-top: 1rem;
        color: #6c757d;
        font-size: 0.875rem;
    }
    
    .numbers-section {
        background: white;
        border-radius: 25px;
        padding: 2.5rem;
        margin-top: 3rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    }
    
    .section-title {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .section-title-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }
    
    .section-title h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a2e;
    }
    
    .legend-bar {
        display: flex;
        gap: 2rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .legend-color {
        width: 20px;
        height: 20px;
        border-radius: 5px;
    }
    
    .legend-color.available {
        background: linear-gradient(135deg, #28a745, #20c997);
    }
    
    .legend-color.reserved {
        background: linear-gradient(135deg, #ffc107, #ffca2c);
    }
    
    .legend-color.sold {
        background: linear-gradient(135deg, #dc3545, #c82333);
    }
    
    .number-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(55px, 1fr));
        gap: 8px;
    }
    
    .number-item {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: default;
        transition: all 0.2s ease;
    }
    
    .number-item.available {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
    }
    
    .number-item.reserved {
        background: linear-gradient(135deg, #fff3cd, #ffeeba);
        color: #856404;
    }
    
    .number-item.sold {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        color: #721c24;
    }
    
    .number-item:hover {
        transform: scale(1.1);
        z-index: 5;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .description-card {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1.5rem;
        margin: 1.5rem 0;
        border-left: 4px solid #667eea;
    }
    
    .description-card h5 {
        font-weight: 700;
        color: #667eea;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    @media (max-width: 768px) {
        .raffle-hero {
            padding: 5rem 0 3rem;
        }
        
        .product-showcase {
            margin-top: -4rem;
        }
        
        .product-image-card img {
            height: 280px;
        }
        
        .raffle-title {
            font-size: 1.75rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .number-grid {
            grid-template-columns: repeat(auto-fill, minmax(45px, 1fr));
        }
    }
</style>

<!-- Hero Section -->
<section class="raffle-hero">
    <div class="container">
        <nav class="breadcrumb breadcrumb-modern" aria-label="breadcrumbs">
            <ul>
                <li><a href="<?= site_url('/') ?>"><i class="fas fa-home"></i> Início</a></li>
                <li><a href="<?= site_url('rifas') ?>">Rifas</a></li>
                <li class="is-active"><a href="#" aria-current="page"><?= esc($raffle->name) ?></a></li>
            </ul>
        </nav>
    </div>
</section>

<!-- Product Showcase -->
<section class="section product-showcase">
    <div class="container">
        <div class="columns is-variable is-8">
            <!-- Imagem do Produto -->
            <div class="column is-5">
                <div class="product-image-card">
                    <div class="status-badge-floating <?= $raffle->isActive() ? 'active' : 'finished' ?>">
                        <?php if ($raffle->isActive()): ?>
                            <i class="fas fa-bolt"></i> <?= $raffle->getStatusLabel() ?>
                        <?php else: ?>
                            <i class="fas fa-flag-checkered"></i> <?= $raffle->getStatusLabel() ?>
                        <?php endif; ?>
                    </div>
                    <img src="<?= $raffleImage ?>" alt="<?= esc($raffle->name) ?>">
                </div>
                
                <?php if ($raffle->isFinished() && !empty($winners)): ?>
                    <div class="winner-showcase">
                        <h4><i class="fas fa-trophy"></i> Resultado do Sorteio</h4>
                        <?php foreach ($winners as $winner): ?>
                            <div class="winner-item">
                                <span class="winner-number"><?= $winner->getFormattedNumber($raffle->getNumberDigits()) ?></span>
                                <div>
                                    <strong><?= $winner->getPrizePositionLabel() ?></strong><br>
                                    <span><?= esc($winner->winner_name) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Informações do Produto -->
            <div class="column is-7">
                <div class="info-card">
                    <h1 class="raffle-title"><?= esc($raffle->name) ?></h1>
                    
                    <?php if ($raffle->prize_description): ?>
                        <div class="prize-highlight">
                            <strong><i class="fas fa-gift"></i> Prêmio</strong>
                            <p><?= esc($raffle->prize_description) ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($raffle->description): ?>
                        <div class="description-card">
                            <h5><i class="fas fa-info-circle"></i> Descrição</h5>
                            <p><?= nl2br(esc($raffle->description)) ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Estatísticas -->
                    <div class="stats-grid">
                        <div class="stat-item price">
                            <div class="label">Preço por Número</div>
                            <div class="value"><?= $raffle->getFormattedPrice() ?></div>
                        </div>
                        <div class="stat-item date">
                            <div class="label">Data do Sorteio</div>
                            <div class="value"><?= $raffle->getFormattedDrawDate() ?></div>
                        </div>
                    </div>

                    <!-- Progresso -->
                    <?php if (isset($stats) && $stats['total'] > 0): ?>
                        <?php $percentage = round(($stats['sold'] / $stats['total']) * 100); ?>
                        <div class="progress-section">
                            <div class="progress-header">
                                <span class="has-text-weight-bold">Progresso da Rifa</span>
                                <div class="progress-stats">
                                    <div class="progress-stat available">
                                        <div class="num"><?= number_format($stats['available'], 0, ',', '.') ?></div>
                                        <div class="lbl">Disponíveis</div>
                                    </div>
                                    <div class="progress-stat sold">
                                        <div class="num"><?= number_format($stats['sold'], 0, ',', '.') ?></div>
                                        <div class="lbl">Vendidos</div>
                                    </div>
                                </div>
                            </div>
                            <div class="progress-bar-modern">
                                <div class="progress-bar-fill" style="width: <?= $percentage ?>%"></div>
                            </div>
                            <div class="progress-percentage">
                                <i class="fas fa-chart-line"></i> <?= $percentage ?>% vendido
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Botão de Compra -->
                    <?php if ($raffle->isActive()): ?>
                        <a href="<?= site_url('comprar/' . $raffle->slug) ?>" class="buy-button">
                            <i class="fas fa-shopping-cart"></i>
                            Comprar Números Agora
                        </a>
                        <p class="buy-limits">
                            <i class="fas fa-info-circle"></i>
                            Mínimo: <?= $raffle->min_per_purchase ?> número(s) | Máximo: <?= $raffle->max_per_purchase ?> números por compra
                        </p>
                    <?php else: ?>
                        <button class="buy-button disabled" disabled>
                            <i class="fas fa-lock"></i>
                            Rifa Encerrada
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Grade de Números -->
        <?php if (!empty($numbers)): ?>
        <div class="numbers-section">
            <div class="section-title">
                <div class="section-title-icon">
                    <i class="fas fa-th"></i>
                </div>
                <div>
                    <h3>Números da Rifa</h3>
                    <p class="has-text-grey">Confira a disponibilidade dos números</p>
                </div>
            </div>
            
            <div class="legend-bar">
                <div class="legend-item">
                    <div class="legend-color available"></div>
                    <span>Disponível</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color reserved"></div>
                    <span>Reservado</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color sold"></div>
                    <span>Vendido</span>
                </div>
            </div>

            <div class="number-grid">
                <?php foreach ($numbers as $number): ?>
                    <div class="number-item <?= $number->status ?>" 
                         title="Número <?= $number->getFormattedNumber($raffle->getNumberDigits()) ?> - <?= $number->getStatusLabel() ?>">
                        <?= $number->getFormattedNumber($raffle->getNumberDigits()) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>
