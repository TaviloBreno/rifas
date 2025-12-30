<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Início<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="hero is-primary is-medium">
    <div class="hero-body">
        <div class="container has-text-centered">
            <h1 class="title is-1">
                <i class="fas fa-ticket-alt"></i> <?= esc($settings['site_name'] ?? 'Rifas Online') ?>
            </h1>
            <h2 class="subtitle is-4">
                <?= esc($settings['site_description'] ?? 'Participe das melhores rifas e concorra a prêmios incríveis!') ?>
            </h2>
        </div>
    </div>
</section>

<!-- Rifas em Destaque -->
<?php if (!empty($featuredRaffles)): ?>
<section class="section">
    <div class="container">
        <h2 class="title is-3 has-text-centered">
            <i class="fas fa-star has-text-warning"></i> Rifas em Destaque
        </h2>
        <div class="columns is-multiline">
            <?php foreach ($featuredRaffles as $raffle): ?>
                <div class="column is-4">
                    <div class="card">
                        <div class="card-image">
                            <figure class="image is-4by3">
                                <img src="<?= esc($raffle->getImageUrl()) ?>" alt="<?= esc($raffle->name) ?>" class="raffle-image">
                            </figure>
                            <span class="tag is-warning" style="position: absolute; top: 10px; right: 10px;">
                                <i class="fas fa-star"></i> Destaque
                            </span>
                        </div>
                        <div class="card-content">
                            <p class="title is-4"><?= esc($raffle->name) ?></p>
                            <p class="subtitle is-6 has-text-grey">
                                <?= esc($raffle->getShortDescription(80)) ?>
                            </p>
                            <div class="content">
                                <div class="level is-mobile">
                                    <div class="level-left">
                                        <span class="tag is-large is-success">
                                            <strong><?= $raffle->getFormattedPrice() ?></strong>
                                        </span>
                                    </div>
                                    <div class="level-right">
                                        <span class="tag is-info">
                                            <i class="fas fa-ticket-alt mr-1"></i> <?= number_format($raffle->quantity, 0, ',', '.') ?> números
                                        </span>
                                    </div>
                                </div>
                                <?php if ($raffle->draw_date): ?>
                                    <p class="has-text-grey-light is-size-7">
                                        <i class="fas fa-calendar"></i> Sorteio: <?= $raffle->getFormattedDrawDate() ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <footer class="card-footer">
                            <a href="<?= site_url('rifa/' . $raffle->slug) ?>" class="card-footer-item has-text-info">
                                <i class="fas fa-eye mr-1"></i> Ver Detalhes
                            </a>
                            <a href="<?= site_url('comprar/' . $raffle->slug) ?>" class="card-footer-item has-text-success has-background-success-light">
                                <i class="fas fa-shopping-cart mr-1"></i> Comprar
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
<section class="section has-background-light">
    <div class="container">
        <h2 class="title is-3 has-text-centered">
            <i class="fas fa-list"></i> Rifas Disponíveis
        </h2>
        
        <?php if (empty($raffles)): ?>
            <div class="notification is-info has-text-centered">
                <p><i class="fas fa-info-circle"></i> Nenhuma rifa disponível no momento.</p>
                <p>Volte em breve para conferir novas oportunidades!</p>
            </div>
        <?php else: ?>
            <div class="columns is-multiline">
                <?php foreach ($raffles as $raffle): ?>
                    <div class="column is-4">
                        <div class="card">
                            <div class="card-image">
                                <figure class="image is-4by3">
                                    <img src="<?= esc($raffle->getImageUrl()) ?>" alt="<?= esc($raffle->name) ?>" class="raffle-image">
                                </figure>
                                <?php if ($raffle->isFeatured()): ?>
                                    <span class="tag is-warning" style="position: absolute; top: 10px; right: 10px;">
                                        <i class="fas fa-star"></i>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="card-content">
                                <p class="title is-5"><?= esc($raffle->name) ?></p>
                                <p class="subtitle is-6 has-text-grey">
                                    <?= esc($raffle->getShortDescription(60)) ?>
                                </p>
                                <div class="content">
                                    <div class="level is-mobile">
                                        <div class="level-left">
                                            <span class="tag is-medium is-success">
                                                <?= $raffle->getFormattedPrice() ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <footer class="card-footer">
                                <a href="<?= site_url('rifa/' . $raffle->slug) ?>" class="card-footer-item">
                                    <i class="fas fa-eye mr-1"></i> Ver
                                </a>
                                <a href="<?= site_url('comprar/' . $raffle->slug) ?>" class="card-footer-item has-text-success">
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
<section class="section">
    <div class="container">
        <h2 class="title is-3 has-text-centered">
            <i class="fas fa-trophy has-text-warning"></i> Últimos Ganhadores
        </h2>
        <div class="columns is-multiline is-centered">
            <?php foreach ($recentWinners as $winner): ?>
                <div class="column is-narrow">
                    <div class="box has-text-centered">
                        <span class="winner-badge">
                            <i class="fas fa-trophy"></i> #<?= str_pad($winner->winning_number, 4, '0', STR_PAD_LEFT) ?>
                        </span>
                        <p class="mt-3"><strong><?= esc($winner->winner_name) ?></strong></p>
                        <p class="has-text-grey is-size-7"><?= esc($winner->raffle_name ?? 'Rifa') ?></p>
                        <p class="has-text-grey-light is-size-7"><?= $winner->getFormattedDrawDate() ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="has-text-centered mt-4">
            <a href="<?= site_url('ganhadores') ?>" class="button is-info is-outlined">
                <i class="fas fa-list mr-1"></i> Ver Todos os Ganhadores
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Como Funciona -->
<section class="section has-background-light">
    <div class="container">
        <h2 class="title is-3 has-text-centered">
            <i class="fas fa-question-circle"></i> Como Funciona?
        </h2>
        <div class="columns is-centered">
            <div class="column is-3 has-text-centered">
                <span class="icon is-large has-text-primary">
                    <i class="fas fa-3x fa-search"></i>
                </span>
                <h3 class="title is-5 mt-3">1. Escolha uma Rifa</h3>
                <p>Navegue pelas rifas disponíveis e escolha a que mais te interessa.</p>
            </div>
            <div class="column is-3 has-text-centered">
                <span class="icon is-large has-text-success">
                    <i class="fas fa-3x fa-hand-pointer"></i>
                </span>
                <h3 class="title is-5 mt-3">2. Selecione seus Números</h3>
                <p>Escolha seus números da sorte de forma rápida e fácil.</p>
            </div>
            <div class="column is-3 has-text-centered">
                <span class="icon is-large has-text-info">
                    <i class="fas fa-3x fa-qrcode"></i>
                </span>
                <h3 class="title is-5 mt-3">3. Pague via PIX</h3>
                <p>Realize o pagamento de forma segura e instantânea via PIX.</p>
            </div>
            <div class="column is-3 has-text-centered">
                <span class="icon is-large has-text-warning">
                    <i class="fas fa-3x fa-trophy"></i>
                </span>
                <h3 class="title is-5 mt-3">4. Aguarde o Sorteio</h3>
                <p>Acompanhe o sorteio e torça para ser o próximo ganhador!</p>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
