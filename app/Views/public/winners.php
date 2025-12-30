<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Ganhadores<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
    .winners-hero {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        padding: 6rem 0 4rem;
        margin-top: -2rem;
        position: relative;
        text-align: center;
        overflow: hidden;
    }
    
    .winners-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .trophy-icon {
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    .trophy-icon i {
        font-size: 3rem;
        color: #333;
    }
    
    .winners-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #333;
        margin-bottom: 0.5rem;
    }
    
    .winners-subtitle {
        font-size: 1.25rem;
        color: rgba(0, 0, 0, 0.7);
    }
    
    .winners-main {
        margin-top: -3rem;
        position: relative;
        z-index: 10;
        padding-bottom: 3rem;
    }
    
    .empty-state {
        background: white;
        border-radius: 25px;
        padding: 4rem 2rem;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1.5rem;
    }
    
    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        color: #6c757d;
        margin-bottom: 1.5rem;
    }
    
    .empty-state .cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .empty-state .cta-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .winner-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .winner-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }
    
    .winner-card-image {
        position: relative;
        height: 200px;
        overflow: hidden;
    }
    
    .winner-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .winner-card-image.no-image {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .winner-card-image.no-image i {
        font-size: 4rem;
        color: rgba(255, 255, 255, 0.3);
    }
    
    .winner-number-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        color: #333;
        padding: 0.75rem 1.25rem;
        border-radius: 50px;
        font-weight: 800;
        font-size: 1.25rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .winner-card-body {
        padding: 1.5rem;
        text-align: center;
    }
    
    .winner-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 0.25rem;
    }
    
    .winner-raffle {
        color: #6c757d;
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }
    
    .winner-prize {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .winner-date {
        color: #adb5bd;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .claimed-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 1rem;
    }
    
    .position-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .position-badge.first { color: #FFD700; }
    .position-badge.second { color: #C0C0C0; }
    .position-badge.third { color: #CD7F32; }
    
    .confetti {
        position: absolute;
        width: 10px;
        height: 10px;
        background: #FFD700;
        animation: fall 3s linear infinite;
    }
    
    @keyframes fall {
        to {
            transform: translateY(100vh) rotate(720deg);
        }
    }
    
    @media (max-width: 768px) {
        .winners-title {
            font-size: 1.75rem;
        }
        
        .winner-card-image {
            height: 160px;
        }
    }
</style>

<?php
// Fallback images do Unsplash para rifas sem imagem
$fallbackImages = [
    'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&q=80',
    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&q=80',
    'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=400&q=80',
    'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&q=80',
    'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=400&q=80',
];
?>

<!-- Hero Section -->
<section class="winners-hero">
    <div class="container">
        <div class="trophy-icon">
            <i class="fas fa-trophy"></i>
        </div>
        <h1 class="winners-title">
            🏆 Hall dos Ganhadores
        </h1>
        <p class="winners-subtitle">
            Confira quem já realizou o sonho de ganhar nas nossas rifas!
        </p>
    </div>
</section>

<!-- Main Content -->
<section class="section winners-main">
    <div class="container">
        <?php if (empty($winners)): ?>
            <div class="empty-state">
                <i class="fas fa-medal"></i>
                <h3>Ainda não temos ganhadores registrados</h3>
                <p>Participe das nossas rifas e seja o primeiro a aparecer aqui!</p>
                <a href="<?= site_url('/') ?>" class="cta-btn">
                    <i class="fas fa-ticket-alt"></i> Ver Rifas Disponíveis
                </a>
            </div>
        <?php else: ?>
            <div class="columns is-multiline">
                <?php $index = 0; foreach ($winners as $winner): $index++; ?>
                    <div class="column is-4">
                        <div class="winner-card">
                            <div class="winner-card-image <?= empty($winner->raffle_image) ? 'no-image' : '' ?>">
                                <?php if (!empty($winner->raffle_image)): ?>
                                    <img src="<?= base_url('uploads/' . $winner->raffle_image) ?>" alt="<?= esc($winner->raffle_name ?? 'Rifa') ?>">
                                <?php else: ?>
                                    <img src="<?= $fallbackImages[($winner->id ?? $index) % count($fallbackImages)] ?>" alt="<?= esc($winner->raffle_name ?? 'Rifa') ?>">
                                <?php endif; ?>
                                
                                <?php if ($index <= 3): ?>
                                    <div class="position-badge <?= $index === 1 ? 'first' : ($index === 2 ? 'second' : 'third') ?>">
                                        <?php if ($index === 1): ?>
                                            <i class="fas fa-crown"></i>
                                        <?php else: ?>
                                            <?= $index ?>º
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="winner-number-badge">
                                    <i class="fas fa-ticket-alt"></i>
                                    #<?= $winner->getFormattedNumber() ?>
                                </div>
                            </div>
                            
                            <div class="winner-card-body">
                                <h3 class="winner-name"><?= esc($winner->winner_name) ?></h3>
                                <p class="winner-raffle"><?= esc($winner->raffle_name ?? 'Rifa') ?></p>
                                
                                <?php if ($winner->prize_description): ?>
                                    <div class="winner-prize">
                                        <i class="fas fa-gift"></i>
                                        <?= esc($winner->prize_description) ?>
                                    </div>
                                <?php endif; ?>
                                
                                <p class="winner-date">
                                    <i class="fas fa-calendar-check"></i>
                                    Sorteado em <?= $winner->getFormattedDrawDate() ?>
                                </p>
                                
                                <?php if ($winner->isClaimed()): ?>
                                    <div class="claimed-badge">
                                        <i class="fas fa-check-circle"></i>
                                        Prêmio Resgatado
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>
