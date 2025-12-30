<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Minhas Rifas<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
    .admin-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 5rem 0 3.5rem;
        margin-top: -2rem;
        position: relative;
        color: white;
    }
    
    .admin-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .admin-hero .container {
        position: relative;
        z-index: 2;
    }
    
    .admin-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .admin-title i {
        font-size: 1.75rem;
    }
    
    .admin-subtitle {
        opacity: 0.9;
    }
    
    .admin-main {
        margin-top: -2rem;
        position: relative;
        z-index: 10;
        padding-bottom: 3rem;
    }
    
    .action-bar {
        background: white;
        border-radius: 20px;
        padding: 1.5rem 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .stats-info {
        font-size: 1rem;
        color: #6c757d;
    }
    
    .stats-info strong {
        color: #1a1a2e;
        font-size: 1.25rem;
    }
    
    .new-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.75rem;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .new-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
        color: white;
    }
    
    .empty-state {
        background: white;
        border-radius: 25px;
        padding: 4rem 2rem;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    }
    
    .empty-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }
    
    .empty-icon i {
        font-size: 3rem;
        color: #adb5bd;
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
    
    .raffle-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .raffle-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }
    
    .raffle-image-wrap {
        position: relative;
        height: 180px;
        overflow: hidden;
    }
    
    .raffle-image-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .raffle-card:hover .raffle-image-wrap img {
        transform: scale(1.05);
    }
    
    .status-tag {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-tag.active {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .status-tag.pending {
        background: linear-gradient(135deg, #ffc107, #ffca2c);
        color: #1a1a2e;
    }
    
    .status-tag.finished {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
    }
    
    .raffle-content {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .raffle-name {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 0.5rem;
    }
    
    .raffle-desc {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 1rem;
        flex: 1;
    }
    
    .raffle-stats {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }
    
    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .stat-badge.price {
        background: #d4edda;
        color: #155724;
    }
    
    .stat-badge.qty {
        background: #d1ecf1;
        color: #0c5460;
    }
    
    .stat-badge.date {
        background: #fff3cd;
        color: #856404;
    }
    
    .raffle-actions {
        display: flex;
        border-top: 2px solid #f0f0f0;
    }
    
    .raffle-actions a {
        flex: 1;
        padding: 1rem;
        text-align: center;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .raffle-actions a:not(:last-child) {
        border-right: 2px solid #f0f0f0;
    }
    
    .raffle-actions .view-btn {
        color: #667eea;
    }
    
    .raffle-actions .view-btn:hover {
        background: rgba(102, 126, 234, 0.1);
    }
    
    .raffle-actions .edit-btn {
        color: #ffc107;
    }
    
    .raffle-actions .edit-btn:hover {
        background: rgba(255, 193, 7, 0.1);
    }
    
    .raffle-actions .delete-btn {
        color: #dc3545;
    }
    
    .raffle-actions .delete-btn:hover {
        background: rgba(220, 53, 69, 0.1);
    }
    
    /* Modern Modal */
    .modal-card {
        border-radius: 20px;
        overflow: hidden;
    }
    
    .modal-card-head {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border: none;
    }
    
    .modal-card-title {
        color: white;
        font-weight: 700;
    }
    
    .modal-card-head .delete {
        background: rgba(255,255,255,0.3);
    }
    
    .modal-card-foot {
        background: #f8f9fa;
        border: none;
        justify-content: flex-end;
        gap: 0.75rem;
    }
    
    .modal-card-foot .button.is-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border: none;
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .admin-title {
            font-size: 1.5rem;
        }
        
        .action-bar {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<!-- Hero Section -->
<section class="admin-hero">
    <div class="container">
        <h1 class="admin-title">
            <i class="fas fa-ticket-alt"></i> Minhas Rifas
        </h1>
        <p class="admin-subtitle">Gerencie suas rifas de forma simples e prática</p>
    </div>
</section>

<!-- Main Content -->
<section class="section admin-main">
    <div class="container">
        
        <!-- Action Bar -->
        <div class="action-bar">
            <div class="stats-info">
                <strong><?= count($raffles) ?></strong> rifa(s) encontrada(s)
            </div>
            <a href="<?= site_url('raffles/new') ?>" class="new-btn">
                <i class="fas fa-plus"></i> Nova Rifa
            </a>
        </div>

        <?php if (empty($raffles)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <h3>Nenhuma rifa cadastrada</h3>
                <p>Você ainda não possui rifas. Crie sua primeira rifa agora!</p>
                <a href="<?= site_url('raffles/new') ?>" class="new-btn">
                    <i class="fas fa-plus"></i> Criar Primeira Rifa
                </a>
            </div>
        <?php else: ?>
            <div class="columns is-multiline">
                <?php foreach ($raffles as $raffle): ?>
                    <?php
                    $statusClass = 'active';
                    if ($raffle->status === 'pending') $statusClass = 'pending';
                    elseif ($raffle->status === 'finished' || $raffle->status === 'cancelled') $statusClass = 'finished';
                    ?>
                    <div class="column is-4">
                        <div class="raffle-card">
                            <div class="raffle-image-wrap">
                                <img src="<?= $raffle->getImageUrl() ?>" alt="<?= esc($raffle->name) ?>">
                                <span class="status-tag <?= $statusClass ?>">
                                    <?= $raffle->getStatusLabel() ?>
                                </span>
                            </div>
                            <div class="raffle-content">
                                <h3 class="raffle-name"><?= esc($raffle->name) ?></h3>
                                <p class="raffle-desc"><?= esc($raffle->getShortDescription(80)) ?></p>
                                
                                <div class="raffle-stats">
                                    <span class="stat-badge price">
                                        <i class="fas fa-tag"></i> <?= $raffle->getFormattedPrice() ?>
                                    </span>
                                    <span class="stat-badge qty">
                                        <i class="fas fa-hashtag"></i> <?= $raffle->quantity ?>
                                    </span>
                                    <span class="stat-badge date">
                                        <i class="fas fa-calendar"></i> <?= $raffle->getFormattedDrawDate() ?>
                                    </span>
                                </div>
                            </div>
                            <div class="raffle-actions">
                                <a href="<?= site_url('raffles/' . $raffle->id) ?>" class="view-btn">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                <a href="<?= site_url('raffles/' . $raffle->id . '/edit') ?>" class="edit-btn">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="#" class="delete-btn" onclick="confirmDelete(<?= $raffle->id ?>)">
                                    <i class="fas fa-trash"></i> Excluir
                                </a>
                            </div>
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
            <p class="modal-card-title"><i class="fas fa-exclamation-triangle mr-2"></i> Confirmar Exclusão</p>
            <button class="delete" aria-label="close" onclick="closeModal()"></button>
        </header>
        <section class="modal-card-body">
            <p>Tem certeza que deseja excluir esta rifa?</p>
            <p class="has-text-danger"><strong>Esta ação não pode ser desfeita!</strong></p>
        </section>
        <footer class="modal-card-foot">
            <button class="button" onclick="closeModal()">Cancelar</button>
            <form id="deleteForm" method="post" style="display: inline;">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="button is-danger">
                    <i class="fas fa-trash mr-1"></i> Sim, excluir
                </button>
            </form>
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

    document.querySelector('.modal-background').addEventListener('click', closeModal);
</script>

<?= $this->endSection() ?>
