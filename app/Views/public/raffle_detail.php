<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= esc($raffle->name) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="container">
        <nav class="breadcrumb" aria-label="breadcrumbs">
            <ul>
                <li><a href="<?= site_url('/') ?>">Início</a></li>
                <li class="is-active"><a href="#" aria-current="page"><?= esc($raffle->name) ?></a></li>
            </ul>
        </nav>

        <div class="columns">
            <!-- Imagem e Info Principal -->
            <div class="column is-6">
                <figure class="image is-4by3">
                    <img src="<?= esc($raffle->getImageUrl()) ?>" alt="<?= esc($raffle->name) ?>" style="border-radius: 10px; object-fit: cover;">
                </figure>
                
                <?php if ($raffle->isFinished() && !empty($winners)): ?>
                    <div class="notification is-warning mt-4">
                        <h4 class="title is-5">
                            <i class="fas fa-trophy"></i> Resultado do Sorteio
                        </h4>
                        <?php foreach ($winners as $winner): ?>
                            <p>
                                <strong><?= $winner->getPrizePositionLabel() ?>:</strong> 
                                Número <span class="tag is-large is-success"><?= $winner->getFormattedNumber($raffle->getNumberDigits()) ?></span>
                                - <?= esc($winner->winner_name) ?>
                            </p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Detalhes -->
            <div class="column is-6">
                <h1 class="title is-2"><?= esc($raffle->name) ?></h1>
                
                <span class="tag <?= $raffle->getStatusClass() ?> is-medium mb-3">
                    <?= $raffle->getStatusLabel() ?>
                </span>
                
                <?php if ($raffle->prize_description): ?>
                    <div class="notification is-info is-light">
                        <strong><i class="fas fa-gift"></i> Prêmio:</strong>
                        <?= esc($raffle->prize_description) ?>
                    </div>
                <?php endif; ?>

                <div class="content">
                    <?= nl2br(esc($raffle->description)) ?>
                </div>

                <!-- Estatísticas -->
                <div class="stats-box mb-4">
                    <div class="columns is-mobile is-multiline">
                        <div class="column is-6">
                            <p class="heading">Preço por Número</p>
                            <p class="title is-4 has-text-success"><?= $raffle->getFormattedPrice() ?></p>
                        </div>
                        <div class="column is-6">
                            <p class="heading">Data do Sorteio</p>
                            <p class="title is-5"><?= $raffle->getFormattedDrawDate() ?></p>
                        </div>
                        <div class="column is-4">
                            <p class="heading">Total</p>
                            <p class="title is-5"><?= number_format($stats['total'], 0, ',', '.') ?></p>
                        </div>
                        <div class="column is-4">
                            <p class="heading">Disponíveis</p>
                            <p class="title is-5 has-text-success"><?= number_format($stats['available'], 0, ',', '.') ?></p>
                        </div>
                        <div class="column is-4">
                            <p class="heading">Vendidos</p>
                            <p class="title is-5 has-text-danger"><?= number_format($stats['sold'], 0, ',', '.') ?></p>
                        </div>
                    </div>
                    
                    <?php if ($stats['total'] > 0): ?>
                        <?php $percentage = round(($stats['sold'] / $stats['total']) * 100); ?>
                        <div class="progress-container">
                            <progress class="progress is-success" value="<?= $stats['sold'] ?>" max="<?= $stats['total'] ?>">
                                <?= $percentage ?>%
                            </progress>
                            <p class="has-text-centered has-text-grey"><?= $percentage ?>% vendido</p>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($raffle->isActive()): ?>
                    <a href="<?= site_url('comprar/' . $raffle->slug) ?>" class="button is-success is-large is-fullwidth">
                        <span class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </span>
                        <span>Comprar Números</span>
                    </a>
                    
                    <p class="has-text-grey has-text-centered mt-2">
                        <small>
                            Mínimo: <?= $raffle->min_per_purchase ?> número(s) | 
                            Máximo: <?= $raffle->max_per_purchase ?> números por compra
                        </small>
                    </p>
                <?php else: ?>
                    <button class="button is-dark is-large is-fullwidth" disabled>
                        <span class="icon">
                            <i class="fas fa-lock"></i>
                        </span>
                        <span>Rifa Encerrada</span>
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Grade de Números -->
        <?php if (!empty($numbers)): ?>
        <div class="box mt-5">
            <h3 class="title is-4">
                <i class="fas fa-th"></i> Números da Rifa
            </h3>
            
            <div class="mb-4">
                <span class="tag is-success">
                    <span class="icon"><i class="fas fa-square"></i></span>
                    <span>Disponível</span>
                </span>
                <span class="tag is-warning ml-2">
                    <span class="icon"><i class="fas fa-square"></i></span>
                    <span>Reservado</span>
                </span>
                <span class="tag is-danger ml-2">
                    <span class="icon"><i class="fas fa-square"></i></span>
                    <span>Vendido</span>
                </span>
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
