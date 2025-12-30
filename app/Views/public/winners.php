<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Ganhadores<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="hero is-warning is-small">
    <div class="hero-body">
        <div class="container has-text-centered">
            <h1 class="title">
                <i class="fas fa-trophy"></i> Ganhadores
            </h1>
            <p class="subtitle">Confira quem já ganhou nas nossas rifas!</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (empty($winners)): ?>
            <div class="notification is-info has-text-centered">
                <p><i class="fas fa-info-circle"></i> Ainda não temos ganhadores registrados.</p>
                <p>Participe das nossas rifas e seja o próximo!</p>
                <a href="<?= site_url('/') ?>" class="button is-primary mt-3">
                    <i class="fas fa-ticket-alt mr-1"></i> Ver Rifas Disponíveis
                </a>
            </div>
        <?php else: ?>
            <div class="columns is-multiline">
                <?php foreach ($winners as $winner): ?>
                    <div class="column is-4">
                        <div class="card">
                            <?php if (!empty($winner->raffle_image)): ?>
                            <div class="card-image">
                                <figure class="image is-4by3">
                                    <img src="<?= base_url('uploads/' . $winner->raffle_image) ?>" alt="<?= esc($winner->raffle_name ?? 'Rifa') ?>">
                                </figure>
                            </div>
                            <?php endif; ?>
                            <div class="card-content has-text-centered">
                                <span class="winner-badge is-size-4">
                                    <i class="fas fa-trophy"></i> 
                                    #<?= $winner->getFormattedNumber() ?>
                                </span>
                                
                                <p class="title is-4 mt-4"><?= esc($winner->winner_name) ?></p>
                                <p class="subtitle is-6 has-text-grey">
                                    <?= esc($winner->raffle_name ?? 'Rifa') ?>
                                </p>
                                
                                <?php if ($winner->prize_description): ?>
                                    <p class="has-text-success">
                                        <i class="fas fa-gift"></i> <?= esc($winner->prize_description) ?>
                                    </p>
                                <?php endif; ?>
                                
                                <p class="has-text-grey-light mt-3 is-size-7">
                                    <i class="fas fa-calendar"></i> Sorteado em <?= $winner->getFormattedDrawDate() ?>
                                </p>
                                
                                <?php if ($winner->isClaimed()): ?>
                                    <span class="tag is-success mt-2">
                                        <i class="fas fa-check mr-1"></i> Prêmio Resgatado
                                    </span>
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
