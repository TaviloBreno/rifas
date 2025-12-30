<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Início<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.hero-particles {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.hero-particles::before {
    content: '';
    position: absolute;
    width: 300px;
    height: 300px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    top: -100px;
    right: -100px;
    animation: float 6s ease-in-out infinite;
}

.hero-particles::after {
    content: '';
    position: absolute;
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
    bottom: -50px;
    left: -50px;
    animation: float 8s ease-in-out infinite reverse;
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(10deg); }
}

.feature-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.8rem;
}

.testimonial-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    position: relative;
}

.testimonial-card::before {
    content: '"';
    position: absolute;
    top: 10px;
    left: 20px;
    font-size: 4rem;
    color: #667eea;
    opacity: 0.2;
    font-family: Georgia, serif;
}

.testimonial-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #667eea;
}

.raffle-card-premium {
    position: relative;
    overflow: hidden;
}

.raffle-card-premium::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: 0.5s;
    z-index: 1;
}

.raffle-card-premium:hover::before {
    left: 100%;
}

.price-ribbon {
    position: absolute;
    bottom: 20px;
    left: 0;
    background: var(--success-gradient);
    color: white;
    padding: 0.5rem 1.5rem 0.5rem 1rem;
    font-weight: 700;
    font-size: 1.1rem;
    border-radius: 0 25px 25px 0;
    box-shadow: 0 4px 15px rgba(17, 153, 142, 0.4);
    z-index: 10;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="hero is-medium hero-main">
    <div class="hero-particles"></div>
    <div class="hero-body">
        <div class="container has-text-centered">
            <h1 class="title is-1 has-text-white mb-5">
                🎉 Realize Seus Sonhos!
            </h1>
            <h2 class="subtitle is-4 has-text-white mb-6">
                Participe das melhores rifas online e concorra a prêmios incríveis.<br>
                Sua sorte pode mudar hoje!
            </h2>
            <div class="buttons is-centered">
                <a href="#rifas" class="button is-large is-white is-rounded">
                    <span class="icon"><i class="fas fa-ticket-alt"></i></span>
                    <span>Ver Rifas</span>
                </a>
                <a href="<?= site_url('ganhadores') ?>" class="button is-large is-outlined is-white is-rounded">
                    <span class="icon"><i class="fas fa-trophy"></i></span>
                    <span>Ver Ganhadores</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Como Funciona -->
<section class="section">
    <div class="container">
        <h2 class="title is-2 has-text-centered section-title mb-6">Como Funciona</h2>
        <p class="subtitle has-text-centered has-text-grey mb-6">Participar é muito simples!</p>
        
        <div class="columns is-multiline mt-5">
            <div class="column is-3">
                <div class="how-it-works-item">
                    <div class="how-it-works-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="title is-5">1. Escolha</h3>
                    <p class="has-text-grey">Navegue pelas rifas disponíveis e escolha a que mais te interessa.</p>
                </div>
            </div>
            <div class="column is-3">
                <div class="how-it-works-item">
                    <div class="how-it-works-icon" style="background: var(--success-gradient);">
                        <i class="fas fa-hand-pointer"></i>
                    </div>
                    <h3 class="title is-5">2. Selecione</h3>
                    <p class="has-text-grey">Escolha seus números da sorte de forma manual ou aleatória.</p>
                </div>
            </div>
            <div class="column is-3">
                <div class="how-it-works-item">
                    <div class="how-it-works-icon" style="background: var(--gold-gradient);">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <h3 class="title is-5">3. Pague</h3>
                    <p class="has-text-grey">Pague de forma rápida e segura via PIX. Confirmação instantânea!</p>
                </div>
            </div>
            <div class="column is-3">
                <div class="how-it-works-item">
                    <div class="how-it-works-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3 class="title is-5">4. Ganhe!</h3>
                    <p class="has-text-grey">Acompanhe o sorteio e torça para ser o grande vencedor!</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Rifas em Destaque -->
<?php if (!empty($featuredRaffles)): ?>
<section class="section has-background-light" id="rifas">
    <div class="container">
        <h2 class="title is-2 has-text-centered section-title">
            <i class="fas fa-star has-text-warning"></i> Rifas em Destaque
        </h2>
        <p class="subtitle has-text-centered has-text-grey mb-6">Não perca essas oportunidades incríveis!</p>
        
        <div class="columns is-multiline">
            <?php 
            $unsplashImages = [
                'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&h=400&fit=crop',
                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=400&fit=crop',
                'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=600&h=400&fit=crop',
                'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=600&h=400&fit=crop',
                'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&h=400&fit=crop',
                'https://images.unsplash.com/photo-1600185365926-3a2ce3cdb9eb?w=600&h=400&fit=crop',
            ];
            foreach ($featuredRaffles as $index => $raffle): 
                $imageUrl = $raffle->image ? base_url('uploads/raffles/' . $raffle->image) : $unsplashImages[$index % count($unsplashImages)];
            ?>
                <div class="column is-4">
                    <div class="card raffle-card-premium">
                        <div class="card-image">
                            <figure class="image is-4by3">
                                <img src="<?= esc($imageUrl) ?>" alt="<?= esc($raffle->title) ?>" class="raffle-image" loading="lazy">
                            </figure>
                            <span class="featured-badge">
                                <i class="fas fa-star mr-1"></i> Destaque
                            </span>
                            <span class="price-ribbon">
                                R$ <?= number_format($raffle->price, 2, ',', '.') ?>
                            </span>
                        </div>
                        <div class="card-content">
                            <p class="title is-4"><?= esc($raffle->title) ?></p>
                            <p class="subtitle is-6 has-text-grey">
                                <?= esc(substr($raffle->description ?? '', 0, 80)) ?>...
                            </p>
                            <div class="content">
                                <div class="is-flex is-justify-content-space-between is-align-items-center mb-3">
                                    <span class="tag is-info is-light is-medium">
                                        <i class="fas fa-ticket-alt mr-1"></i> <?= number_format($raffle->total_numbers, 0, ',', '.') ?> números
                                    </span>
                                </div>
                                <?php if ($raffle->draw_date): ?>
                                    <p class="has-text-grey is-size-7">
                                        <i class="fas fa-calendar-alt mr-1"></i> Sorteio: <?= date('d/m/Y \à\s H:i', strtotime($raffle->draw_date)) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <footer class="card-footer">
                            <a href="<?= site_url('rifa/' . $raffle->slug) ?>" class="card-footer-item has-text-weight-semibold">
                                <i class="fas fa-eye mr-2"></i> Ver Detalhes
                            </a>
                            <a href="<?= site_url('checkout/' . $raffle->slug) ?>" class="card-footer-item has-text-success has-text-weight-bold" style="background: rgba(17, 153, 142, 0.1);">
                                <i class="fas fa-shopping-cart mr-2"></i> Participar
                            </a>
                        </footer>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Todas as Rifas -->
<section class="section">
    <div class="container">
        <h2 class="title is-2 has-text-centered section-title">
            <i class="fas fa-ticket-alt"></i> Todas as Rifas
        </h2>
        <p class="subtitle has-text-centered has-text-grey mb-6">Escolha sua rifa e boa sorte!</p>
        
        <?php if (empty($raffles)): ?>
            <div class="notification is-info is-light has-text-centered" style="max-width: 600px; margin: 0 auto;">
                <figure class="image is-128x128 mx-auto mb-4">
                    <img src="https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?w=200&h=200&fit=crop" alt="Sem rifas" style="border-radius: 50%; opacity: 0.7;">
                </figure>
                <p class="is-size-5"><i class="fas fa-info-circle mr-2"></i> Nenhuma rifa disponível no momento.</p>
                <p class="has-text-grey">Volte em breve para conferir novas oportunidades!</p>
            </div>
        <?php else: ?>
            <div class="columns is-multiline">
                <?php 
                $unsplashImages2 = [
                    'https://images.unsplash.com/photo-1491553895911-0055uj4e20a?w=600&h=400&fit=crop',
                    'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=600&h=400&fit=crop',
                    'https://images.unsplash.com/photo-1560343090-f0409e92791a?w=600&h=400&fit=crop',
                    'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&h=400&fit=crop',
                    'https://images.unsplash.com/photo-1512428559087-560fa5ceab42?w=600&h=400&fit=crop',
                    'https://images.unsplash.com/photo-1602524206684-fdf6393a3c89?w=600&h=400&fit=crop',
                ];
                foreach ($raffles as $index => $raffle): 
                    $imageUrl = $raffle->image ? base_url('uploads/raffles/' . $raffle->image) : $unsplashImages2[$index % count($unsplashImages2)];
                ?>
                    <div class="column is-4">
                        <div class="card">
                            <div class="card-image">
                                <figure class="image is-4by3">
                                    <img src="<?= esc($imageUrl) ?>" alt="<?= esc($raffle->title) ?>" class="raffle-image" loading="lazy">
                                </figure>
                                <?php if ($raffle->is_featured): ?>
                                    <span class="featured-badge">
                                        <i class="fas fa-star"></i>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="card-content">
                                <p class="title is-5"><?= esc($raffle->title) ?></p>
                                <p class="subtitle is-6 has-text-grey">
                                    <?= esc(substr($raffle->description ?? '', 0, 60)) ?>...
                                </p>
                                <div class="is-flex is-justify-content-space-between is-align-items-center">
                                    <span class="price-tag">
                                        R$ <?= number_format($raffle->price, 2, ',', '.') ?>
                                    </span>
                                    <span class="tag is-light">
                                        <i class="fas fa-ticket-alt mr-1"></i> <?= number_format($raffle->total_numbers, 0, ',', '.') ?>
                                    </span>
                                </div>
                            </div>
                            <footer class="card-footer">
                                <a href="<?= site_url('rifa/' . $raffle->slug) ?>" class="card-footer-item">
                                    <i class="fas fa-eye mr-1"></i> Ver
                                </a>
                                <a href="<?= site_url('checkout/' . $raffle->slug) ?>" class="card-footer-item has-text-success has-text-weight-semibold">
                                    <i class="fas fa-shopping-cart mr-1"></i> Comprar
                                </a>
                            </footer>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Últimos Ganhadores -->
<?php if (!empty($recentWinners)): ?>
<section class="section has-background-light">
    <div class="container">
        <h2 class="title is-2 has-text-centered section-title">
            <i class="fas fa-trophy has-text-warning"></i> Últimos Ganhadores
        </h2>
        <p class="subtitle has-text-centered has-text-grey mb-6">Veja quem já realizou seus sonhos!</p>
        
        <div class="columns is-multiline is-centered">
            <?php 
            $avatars = [
                'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=face',
                'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop&crop=face',
                'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop&crop=face',
                'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop&crop=face',
            ];
            foreach ($recentWinners as $index => $winner): ?>
                <div class="column is-3">
                    <div class="box has-text-centered" style="border-radius: 16px;">
                        <figure class="image is-64x64 mx-auto mb-3">
                            <img src="<?= $avatars[$index % count($avatars)] ?>" alt="Ganhador" class="is-rounded" style="border: 3px solid #f5af19;">
                        </figure>
                        <span class="winner-badge mb-3" style="display: inline-block;">
                            <i class="fas fa-trophy mr-1"></i> #<?= str_pad($winner['winning_number'] ?? 0, 4, '0', STR_PAD_LEFT) ?>
                        </span>
                        <p class="title is-6 mt-3"><?= esc($winner['customer_name'] ?? 'Ganhador') ?></p>
                        <p class="subtitle is-7 has-text-grey"><?= esc($winner['raffle_title'] ?? 'Rifa') ?></p>
                        <p class="is-size-7 has-text-grey-light">
                            <i class="fas fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($winner['created_at'] ?? 'now')) ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="has-text-centered mt-5">
            <a href="<?= site_url('ganhadores') ?>" class="button btn-primary is-rounded">
                <span class="icon"><i class="fas fa-trophy"></i></span>
                <span>Ver Todos os Ganhadores</span>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Depoimentos -->
<section class="section">
    <div class="container">
        <h2 class="title is-2 has-text-centered section-title">
            <i class="fas fa-heart has-text-danger"></i> O Que Dizem Nossos Ganhadores
        </h2>
        <p class="subtitle has-text-centered has-text-grey mb-6">Histórias reais de pessoas que realizaram seus sonhos!</p>
        
        <div class="columns is-multiline mt-5">
            <div class="column is-4">
                <div class="testimonial-card">
                    <p class="mb-4 has-text-grey">"Nunca imaginei que ia ganhar! Participei da rifa do iPhone e agora estou com ele na mão. Recomendo demais!"</p>
                    <div class="is-flex is-align-items-center">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=60&h=60&fit=crop&crop=face" alt="João" class="testimonial-avatar mr-3">
                        <div>
                            <p class="has-text-weight-bold">João Silva</p>
                            <p class="is-size-7 has-text-grey">São Paulo, SP</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-4">
                <div class="testimonial-card">
                    <p class="mb-4 has-text-grey">"O processo de compra é super fácil e rápido. PIX confirmou na hora! Já ganhei 2 vezes participando."</p>
                    <div class="is-flex is-align-items-center">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=60&h=60&fit=crop&crop=face" alt="Maria" class="testimonial-avatar mr-3">
                        <div>
                            <p class="has-text-weight-bold">Maria Santos</p>
                            <p class="is-size-7 has-text-grey">Rio de Janeiro, RJ</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-4">
                <div class="testimonial-card">
                    <p class="mb-4 has-text-grey">"Site confiável e transparente. Acompanhei o sorteio ao vivo e quando vi meu número sendo sorteado, não acreditei!"</p>
                    <div class="is-flex is-align-items-center">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=60&h=60&fit=crop&crop=face" alt="Pedro" class="testimonial-avatar mr-3">
                        <div>
                            <p class="has-text-weight-bold">Pedro Oliveira</p>
                            <p class="is-size-7 has-text-grey">Belo Horizonte, MG</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Final -->
<section class="section" style="background: var(--primary-gradient);">
    <div class="container has-text-centered">
        <h2 class="title is-2 has-text-white mb-4">
            🎯 Pronto Para Tentar a Sorte?
        </h2>
        <p class="subtitle has-text-white mb-5">
            Escolha seus números da sorte agora e concorra a prêmios incríveis!
        </p>
        <a href="#rifas" class="button is-white is-large is-rounded">
            <span class="icon"><i class="fas fa-ticket-alt"></i></span>
            <span>Participar Agora</span>
        </a>
    </div>
</section>

<?= $this->endSection() ?>
