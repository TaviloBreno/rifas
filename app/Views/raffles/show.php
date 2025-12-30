<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= esc($raffle->name) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="hero is-primary is-small">
    <div class="hero-body">
        <div class="container">
            <h1 class="title">
                <i class="fas fa-ticket-alt"></i> <?= esc($raffle->name) ?>
            </h1>
            <p class="subtitle">
                Detalhes da rifa
            </p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <nav class="breadcrumb" aria-label="breadcrumbs">
            <ul>
                <li><a href="<?= site_url('raffles') ?>">Rifas</a></li>
                <li class="is-active"><a href="#" aria-current="page"><?= esc($raffle->name) ?></a></li>
            </ul>
        </nav>

        <div class="columns">
            <div class="column is-5">
                <div class="card">
                    <div class="card-image">
                        <figure class="image is-4by3">
                            <img src="<?= $raffle->getImageUrl() ?>" alt="<?= esc($raffle->name) ?>">
                        </figure>
                    </div>
                </div>
            </div>
            <div class="column is-7">
                <div class="box">
                    <div class="level">
                        <div class="level-left">
                            <div class="level-item">
                                <span class="tag is-large <?= $raffle->getStatusClass() ?>">
                                    <?= $raffle->getStatusLabel() ?>
                                </span>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <div class="buttons">
                                    <a href="<?= site_url('raffles/' . $raffle->id . '/edit') ?>" class="button is-warning">
                                        <span class="icon"><i class="fas fa-edit"></i></span>
                                        <span>Editar</span>
                                    </a>
                                    <button class="button is-danger" onclick="confirmDelete(<?= $raffle->id ?>)">
                                        <span class="icon"><i class="fas fa-trash"></i></span>
                                        <span>Excluir</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h2 class="title is-3"><?= esc($raffle->name) ?></h2>

                    <?php if ($raffle->description): ?>
                        <div class="content">
                            <p><?= nl2br(esc($raffle->description)) ?></p>
                        </div>
                    <?php endif; ?>

                    <hr>

                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <div class="box has-background-success-light">
                                <p class="heading">Preço por Número</p>
                                <p class="title is-4 has-text-success"><?= $raffle->getFormattedPrice() ?></p>
                            </div>
                        </div>
                        <div class="column is-6">
                            <div class="box has-background-info-light">
                                <p class="heading">Quantidade de Números</p>
                                <p class="title is-4 has-text-info"><?= $raffle->quantity ?></p>
                            </div>
                        </div>
                        <div class="column is-6">
                            <div class="box has-background-warning-light">
                                <p class="heading">Data do Sorteio</p>
                                <p class="title is-4 has-text-warning-dark"><?= $raffle->getFormattedDrawDate() ?></p>
                            </div>
                        </div>
                        <div class="column is-6">
                            <div class="box has-background-primary-light">
                                <p class="heading">Valor Total</p>
                                <p class="title is-4 has-text-primary"><?= $raffle->getFormattedTotalValue() ?></p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="content">
                        <p class="is-size-7 has-text-grey">
                            <strong>Criada em:</strong> <?= $raffle->created_at->format('d/m/Y H:i') ?><br>
                            <strong>Última atualização:</strong> <?= $raffle->updated_at->format('d/m/Y H:i') ?>
                        </p>
                    </div>

                    <div class="buttons">
                        <a href="<?= site_url('raffles') ?>" class="button is-light">
                            <span class="icon"><i class="fas fa-arrow-left"></i></span>
                            <span>Voltar para lista</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal de confirmação de exclusão -->
<div class="modal" id="deleteModal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Confirmar Exclusão</p>
            <button class="delete" aria-label="close" onclick="closeModal()"></button>
        </header>
        <section class="modal-card-body">
            <p>Tem certeza que deseja excluir a rifa <strong><?= esc($raffle->name) ?></strong>?</p>
            <p class="has-text-danger">Esta ação não pode ser desfeita.</p>
        </section>
        <footer class="modal-card-foot">
            <form action="<?= site_url('raffles/' . $raffle->id) ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="button is-danger">Sim, excluir</button>
            </form>
            <button class="button" onclick="closeModal()">Cancelar</button>
        </footer>
    </div>
</div>

<script>
    function confirmDelete(id) {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('is-active');
    }

    function closeModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('is-active');
    }

    document.querySelector('.modal-background').addEventListener('click', closeModal);
</script>

<?= $this->endSection() ?>
