<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Minhas Rifas<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="hero is-primary is-small">
    <div class="hero-body">
        <div class="container">
            <h1 class="title">
                <i class="fas fa-ticket-alt"></i> Minhas Rifas
            </h1>
            <p class="subtitle">
                Gerencie suas rifas de forma simples e prática
            </p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <p class="subtitle is-5">
                        <strong><?= count($raffles) ?></strong> rifa(s) encontrada(s)
                    </p>
                </div>
            </div>
            <div class="level-right">
                <div class="level-item">
                    <a href="<?= site_url('raffles/new') ?>" class="button is-primary">
                        <span class="icon">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span>Nova Rifa</span>
                    </a>
                </div>
            </div>
        </div>

        <?php if (empty($raffles)): ?>
            <div class="notification is-info is-light">
                <p class="has-text-centered">
                    <span class="icon is-large">
                        <i class="fas fa-info-circle fa-2x"></i>
                    </span>
                </p>
                <p class="has-text-centered">
                    Você ainda não possui rifas cadastradas.<br>
                    <a href="<?= site_url('raffles/new') ?>">Clique aqui</a> para criar sua primeira rifa!
                </p>
            </div>
        <?php else: ?>
            <div class="columns is-multiline">
                <?php foreach ($raffles as $raffle): ?>
                    <div class="column is-4">
                        <div class="card">
                            <div class="card-image">
                                <figure class="image is-4by3">
                                    <img src="<?= $raffle->getImageUrl() ?>" alt="<?= esc($raffle->name) ?>" class="raffle-image">
                                </figure>
                            </div>
                            <div class="card-content">
                                <div class="media">
                                    <div class="media-content">
                                        <p class="title is-5"><?= esc($raffle->name) ?></p>
                                        <p class="subtitle is-6">
                                            <span class="tag <?= $raffle->getStatusClass() ?>">
                                                <?= $raffle->getStatusLabel() ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="content">
                                    <p><?= esc($raffle->getShortDescription(80)) ?></p>
                                    
                                    <div class="tags has-addons">
                                        <span class="tag is-dark">Preço</span>
                                        <span class="tag is-success"><?= $raffle->getFormattedPrice() ?></span>
                                    </div>
                                    
                                    <div class="tags has-addons">
                                        <span class="tag is-dark">Números</span>
                                        <span class="tag is-info"><?= $raffle->quantity ?></span>
                                    </div>
                                    
                                    <div class="tags has-addons">
                                        <span class="tag is-dark">Sorteio</span>
                                        <span class="tag is-warning"><?= $raffle->getFormattedDrawDate() ?></span>
                                    </div>
                                </div>
                            </div>
                            <footer class="card-footer">
                                <a href="<?= site_url('raffles/' . $raffle->id) ?>" class="card-footer-item has-text-info">
                                    <span class="icon"><i class="fas fa-eye"></i></span>
                                    <span>Ver</span>
                                </a>
                                <a href="<?= site_url('raffles/' . $raffle->id . '/edit') ?>" class="card-footer-item has-text-warning">
                                    <span class="icon"><i class="fas fa-edit"></i></span>
                                    <span>Editar</span>
                                </a>
                                <a href="#" class="card-footer-item has-text-danger" onclick="confirmDelete(<?= $raffle->id ?>)">
                                    <span class="icon"><i class="fas fa-trash"></i></span>
                                    <span>Excluir</span>
                                </a>
                            </footer>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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
            <p>Tem certeza que deseja excluir esta rifa?</p>
            <p class="has-text-danger">Esta ação não pode ser desfeita.</p>
        </section>
        <footer class="modal-card-foot">
            <form id="deleteForm" method="post">
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
        const form = document.getElementById('deleteForm');
        form.action = '<?= site_url('raffles/') ?>' + id;
        modal.classList.add('is-active');
    }

    function closeModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('is-active');
    }

    // Fecha o modal ao clicar no background
    document.querySelector('.modal-background').addEventListener('click', closeModal);
</script>

<?= $this->endSection() ?>
