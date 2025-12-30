<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<nav class="breadcrumb" aria-label="breadcrumbs">
    <ul>
        <li><a href="<?= site_url('admin/raffles') ?>">Rifas</a></li>
        <li><a href="<?= site_url('admin/raffles/' . $raffle->id) ?>"><?= esc($raffle->title ?? $raffle->name ?? 'Rifa') ?></a></li>
        <li class="is-active"><a href="#" aria-current="page">Prêmios</a></li>
    </ul>
</nav>

<?php if (session()->getFlashdata('success')): ?>
    <div class="notification is-success is-light">
        <button class="delete" onclick="this.parentElement.remove()"></button>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="notification is-danger is-light">
        <button class="delete" onclick="this.parentElement.remove()"></button>
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="level">
    <div class="level-left">
        <div class="level-item">
            <h1 class="title">Prêmios</h1>
        </div>
        <div class="level-item">
            <span class="tag is-info is-light is-medium">
                <?= count($prizes) ?> prêmio(s)
            </span>
        </div>
    </div>
    <div class="level-right">
        <div class="level-item">
            <div class="buttons">
                <a class="button is-light" href="<?= site_url('admin/raffles/' . $raffle->id) ?>">
                    <span class="icon"><i class="fas fa-arrow-left"></i></span>
                    <span>Voltar</span>
                </a>
                <a class="button is-success" href="<?= site_url('admin/raffles/' . $raffle->id . '/prizes/new') ?>">
                    <span class="icon"><i class="fas fa-plus"></i></span>
                    <span>Novo Prêmio</span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php if (empty($prizes)): ?>
    <div class="notification is-warning is-light">
        <p class="mb-3"><strong>Nenhum prêmio cadastrado</strong> para esta rifa.</p>
        <a class="button is-success" href="<?= site_url('admin/raffles/' . $raffle->id . '/prizes/new') ?>">
            <span class="icon"><i class="fas fa-plus"></i></span>
            <span>Criar primeiro prêmio</span>
        </a>
    </div>
<?php else: ?>
    <div class="columns is-multiline">
        <?php foreach ($prizes as $prize): ?>
            <div class="column is-4">
                <div class="card">
                    <div class="card-image">
                        <figure class="image is-4by3">
                            <img src="<?= $prize->getImageUrl() ?>" alt="<?= esc($prize->name) ?>" style="object-fit: cover;">
                        </figure>
                    </div>
                    <div class="card-content">
                        <div class="level is-mobile">
                            <div class="level-left">
                                <div class="level-item">
                                    <p class="title is-5 mb-0"><?= esc($prize->name) ?></p>
                                </div>
                            </div>
                            <div class="level-right">
                                <div class="level-item">
                                    <span class="tag <?= $prize->getPositionClass() ?> is-light">
                                        <?= esc($prize->getPositionLabel()) ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <?php if ($prize->description): ?>
                            <p class="content mb-3"><?= esc($prize->getShortDescription(120)) ?></p>
                        <?php endif; ?>

                        <div class="tags">
                            <?php if ($prize->value): ?>
                                <span class="tag is-success is-light">
                                    <i class="fas fa-money-bill-wave mr-1"></i> <?= esc($prize->getFormattedValue()) ?>
                                </span>
                            <?php endif; ?>

                            <?php if ($prize->isDrawn()): ?>
                                <span class="tag is-info is-light">
                                    <i class="fas fa-trophy mr-1"></i> Nº <?= (int) $prize->winner_number ?>
                                </span>
                            <?php else: ?>
                                <span class="tag is-light">Sem ganhador</span>
                            <?php endif; ?>

                            <?php if ($prize->isClaimed()): ?>
                                <span class="tag is-success">
                                    <i class="fas fa-check mr-1"></i> Retirado
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <footer class="card-footer">
                        <a class="card-footer-item" href="<?= site_url('admin/raffles/' . $raffle->id . '/prizes/' . $prize->id . '/edit') ?>">
                            <span class="icon"><i class="fas fa-edit"></i></span>
                            <span>Editar</span>
                        </a>
                        <a class="card-footer-item has-text-danger" href="#" onclick="confirmDelete(<?= (int) $prize->id ?>)">
                            <span class="icon"><i class="fas fa-trash"></i></span>
                            <span>Excluir</span>
                        </a>
                    </footer>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="modal" id="deleteModal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Confirmar Exclusão</p>
                <button class="delete" aria-label="close" onclick="closeModal()"></button>
            </header>
            <section class="modal-card-body">
                <p>Tem certeza que deseja excluir este prêmio?</p>
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
        function confirmDelete(prizeId) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            form.action = '<?= site_url('admin/raffles/' . $raffle->id . '/prizes/') ?>' + prizeId;
            modal.classList.add('is-active');
        }

        function closeModal() {
            document.getElementById('deleteModal').classList.remove('is-active');
        }

        document.querySelector('#deleteModal .modal-background').addEventListener('click', closeModal);
    </script>
<?php endif; ?>

<?= $this->endSection() ?>
