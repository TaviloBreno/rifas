<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="level">
    <div class="level-left">
        <div class="level-item">
            <h1 class="title">Rifas</h1>
        </div>
    </div>
    <div class="level-right">
        <div class="level-item">
            <a href="<?= site_url('admin/raffles/create') ?>" class="button is-primary">
                <span class="icon"><i class="fas fa-plus"></i></span>
                <span>Nova Rifa</span>
            </a>
        </div>
    </div>
</div>

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

<div class="box">
    <div class="table-container">
        <table class="table is-fullwidth is-striped is-hoverable">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Título</th>
                    <th>Preço</th>
                    <th>Números</th>
                    <th>Vendidos</th>
                    <th>Status</th>
                    <th>Encerramento</th>
                    <th style="width: 200px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($raffles)): ?>
                    <tr>
                        <td colspan="8" class="has-text-centered has-text-grey">
                            Nenhuma rifa cadastrada.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($raffles as $raffle): ?>
                        <tr>
                            <td><?= $raffle->id ?></td>
                            <td>
                                <div class="is-flex is-align-items-center">
                                    <?php if ($raffle->image): ?>
                                        <figure class="image is-32x32 mr-2">
                                            <img src="<?= base_url('uploads/raffles/' . $raffle->image) ?>" alt="<?= esc($raffle->title) ?>" style="object-fit: cover; border-radius: 4px;">
                                        </figure>
                                    <?php endif; ?>
                                    <div>
                                        <strong><?= esc($raffle->title) ?></strong>
                                        <?php if ($raffle->is_featured): ?>
                                            <span class="tag is-warning is-light ml-1">Destaque</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>R$ <?= number_format($raffle->price, 2, ',', '.') ?></td>
                            <td><?= number_format($raffle->total_numbers, 0, '', '.') ?></td>
                            <td>
                                <?php 
                                $soldCount = isset($raffle->sold_count) ? $raffle->sold_count : 0;
                                $percentage = $raffle->total_numbers > 0 ? ($soldCount / $raffle->total_numbers) * 100 : 0;
                                ?>
                                <div>
                                    <progress class="progress is-small <?= $percentage >= 100 ? 'is-success' : ($percentage >= 50 ? 'is-warning' : 'is-info') ?>" value="<?= $percentage ?>" max="100" style="margin-bottom: 0.25rem;"></progress>
                                    <span class="is-size-7"><?= number_format($soldCount, 0, '', '.') ?> (<?= number_format($percentage, 1, ',', '.') ?>%)</span>
                                </div>
                            </td>
                            <td>
                                <?php
                                $statusClass = match($raffle->status) {
                                    'active' => 'is-success',
                                    'pending' => 'is-warning',
                                    'finished' => 'is-info',
                                    'cancelled' => 'is-danger',
                                    default => 'is-light'
                                };
                                $statusLabel = match($raffle->status) {
                                    'active' => 'Ativa',
                                    'pending' => 'Pendente',
                                    'finished' => 'Finalizada',
                                    'cancelled' => 'Cancelada',
                                    default => $raffle->status
                                };
                                ?>
                                <span class="tag <?= $statusClass ?>"><?= $statusLabel ?></span>
                            </td>
                            <td>
                                <?php if ($raffle->end_date): ?>
                                    <?= date('d/m/Y H:i', strtotime($raffle->end_date)) ?>
                                <?php else: ?>
                                    <span class="has-text-grey">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="buttons are-small">
                                    <a href="<?= site_url('admin/raffles/' . $raffle->id) ?>" class="button is-info is-outlined" title="Ver detalhes">
                                        <span class="icon"><i class="fas fa-eye"></i></span>
                                    </a>
                                    <a href="<?= site_url('admin/raffles/' . $raffle->id . '/edit') ?>" class="button is-warning is-outlined" title="Editar">
                                        <span class="icon"><i class="fas fa-edit"></i></span>
                                    </a>
                                    <?php if ($raffle->status === 'active'): ?>
                                        <button type="button" class="button is-success is-outlined" title="Realizar Sorteio" onclick="confirmDraw(<?= $raffle->id ?>)">
                                            <span class="icon"><i class="fas fa-trophy"></i></span>
                                        </button>
                                    <?php endif; ?>
                                    <button type="button" class="button is-danger is-outlined" title="Excluir" onclick="confirmDelete(<?= $raffle->id ?>, '<?= esc($raffle->title) ?>')">
                                        <span class="icon"><i class="fas fa-trash"></i></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if (isset($pager)): ?>
        <nav class="pagination is-centered" role="navigation" aria-label="pagination">
            <?= $pager->links() ?>
        </nav>
    <?php endif; ?>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal" id="deleteModal">
    <div class="modal-background" onclick="closeDeleteModal()"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Confirmar Exclusão</p>
            <button class="delete" aria-label="close" onclick="closeDeleteModal()"></button>
        </header>
        <section class="modal-card-body">
            <p>Tem certeza que deseja excluir a rifa "<strong id="deleteName"></strong>"?</p>
            <p class="has-text-danger mt-2">Esta ação não pode ser desfeita!</p>
        </section>
        <footer class="modal-card-foot">
            <form id="deleteForm" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="button is-danger">Excluir</button>
            </form>
            <button class="button" onclick="closeDeleteModal()">Cancelar</button>
        </footer>
    </div>
</div>

<!-- Modal de Confirmação de Sorteio -->
<div class="modal" id="drawModal">
    <div class="modal-background" onclick="closeDrawModal()"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Realizar Sorteio</p>
            <button class="delete" aria-label="close" onclick="closeDrawModal()"></button>
        </header>
        <section class="modal-card-body">
            <p>Tem certeza que deseja realizar o sorteio desta rifa?</p>
            <p class="has-text-warning mt-2"><strong>Atenção:</strong> O sorteio será realizado entre os números vendidos e a rifa será finalizada.</p>
        </section>
        <footer class="modal-card-foot">
            <form id="drawForm" method="post">
                <?= csrf_field() ?>
                <button type="submit" class="button is-success">Realizar Sorteio</button>
            </form>
            <button class="button" onclick="closeDrawModal()">Cancelar</button>
        </footer>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    document.getElementById('deleteName').textContent = name;
    document.getElementById('deleteForm').action = '<?= site_url('admin/raffles/') ?>' + id;
    document.getElementById('deleteModal').classList.add('is-active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('is-active');
}

function confirmDraw(id) {
    document.getElementById('drawForm').action = '<?= site_url('admin/raffles/') ?>' + id + '/draw';
    document.getElementById('drawModal').classList.add('is-active');
}

function closeDrawModal() {
    document.getElementById('drawModal').classList.remove('is-active');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
        closeDrawModal();
    }
});
</script>
<?= $this->endSection() ?>
