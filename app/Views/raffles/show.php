<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= esc($raffle->name) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
    .detail-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 5rem 0 4rem;
        margin-top: -2rem;
        position: relative;
        color: white;
    }
    
    .detail-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .detail-hero .container {
        position: relative;
        z-index: 2;
    }
    
    .breadcrumb-custom {
        margin-bottom: 1rem;
    }
    
    .breadcrumb-custom a {
        color: rgba(255, 255, 255, 0.8);
        transition: color 0.3s ease;
    }
    
    .breadcrumb-custom a:hover {
        color: white;
    }
    
    .breadcrumb-custom li.is-active a {
        color: white;
    }
    
    .breadcrumb-custom li::before {
        color: rgba(255, 255, 255, 0.5) !important;
    }
    
    .hero-title {
        font-size: 2rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }
    
    .detail-main {
        margin-top: -3rem;
        position: relative;
        z-index: 10;
        padding-bottom: 3rem;
    }
    
    .image-card {
        background: white;
        border-radius: 25px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }
    
    .image-card img {
        width: 100%;
        aspect-ratio: 4/3;
        object-fit: cover;
    }
    
    .detail-card {
        background: white;
        border-radius: 25px;
        padding: 2rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    }
    
    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .status-badge-large {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.875rem;
    }
    
    .status-badge-large.active {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
    }
    
    .status-badge-large.pending {
        background: linear-gradient(135deg, #fff3cd, #ffeeba);
        color: #856404;
    }
    
    .status-badge-large.finished {
        background: linear-gradient(135deg, #e2e3e5, #d6d8db);
        color: #383d41;
    }
    
    .header-actions {
        display: flex;
        gap: 0.75rem;
    }
    
    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .action-btn.edit {
        background: #fff3cd;
        color: #856404;
    }
    
    .action-btn.edit:hover {
        background: #ffc107;
        transform: translateY(-2px);
    }
    
    .action-btn.delete {
        background: #f8d7da;
        color: #721c24;
    }
    
    .action-btn.delete:hover {
        background: #dc3545;
        color: white;
        transform: translateY(-2px);
    }
    
    .detail-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1a1a2e;
        margin-bottom: 1rem;
    }
    
    .detail-description {
        color: #495057;
        line-height: 1.8;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 15px;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .stat-box {
        padding: 1.25rem;
        border-radius: 15px;
        text-align: center;
    }
    
    .stat-box.price {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
    }
    
    .stat-box.qty {
        background: linear-gradient(135deg, #d1ecf1, #bee5eb);
    }
    
    .stat-box.date {
        background: linear-gradient(135deg, #fff3cd, #ffeeba);
    }
    
    .stat-box.total {
        background: linear-gradient(135deg, #e2d4f0, #d4c1ea);
    }
    
    .stat-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    
    .stat-value {
        font-size: 1.25rem;
        font-weight: 800;
        color: #1a1a2e;
    }
    
    .meta-info {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 12px;
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 1.5rem;
    }
    
    .meta-info strong {
        color: #495057;
    }
    
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.5rem;
        background: #f0f0f0;
        color: #495057;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .back-btn:hover {
        background: #e0e0e0;
        color: #1a1a2e;
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
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 1.5rem;
        }
        
        .detail-header {
            flex-direction: column;
        }
        
        .header-actions {
            width: 100%;
        }
        
        .action-btn {
            flex: 1;
            justify-content: center;
        }
    }
</style>

<!-- Hero Section -->
<section class="detail-hero">
    <div class="container">
        <nav class="breadcrumb breadcrumb-custom" aria-label="breadcrumbs">
            <ul>
                <li><a href="<?= site_url('raffles') ?>"><i class="fas fa-ticket-alt mr-1"></i> Rifas</a></li>
                <li class="is-active"><a href="#" aria-current="page"><?= esc($raffle->name) ?></a></li>
            </ul>
        </nav>
        <h1 class="hero-title">
            <i class="fas fa-eye"></i> Detalhes da Rifa
        </h1>
    </div>
</section>

<!-- Main Content -->
<section class="section detail-main">
    <div class="container">
        <div class="columns">
            <div class="column is-5">
                <div class="image-card">
                    <img src="<?= $raffle->getImageUrl() ?>" alt="<?= esc($raffle->name) ?>">
                </div>
            </div>
            <div class="column is-7">
                <div class="detail-card">
                    <div class="detail-header">
                        <?php
                        $statusClass = 'active';
                        if ($raffle->status === 'pending') $statusClass = 'pending';
                        elseif ($raffle->status === 'finished' || $raffle->status === 'cancelled') $statusClass = 'finished';
                        ?>
                        <span class="status-badge-large <?= $statusClass ?>">
                            <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                            <?= $raffle->getStatusLabel() ?>
                        </span>
                        <div class="header-actions">
                            <a href="<?= site_url('raffles/' . $raffle->id . '/edit') ?>" class="action-btn edit">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <button class="action-btn delete" onclick="confirmDelete(<?= $raffle->id ?>)">
                                <i class="fas fa-trash"></i> Excluir
                            </button>
                        </div>
                    </div>

                    <h2 class="detail-title"><?= esc($raffle->name) ?></h2>

                    <?php if ($raffle->description): ?>
                        <div class="detail-description">
                            <?= nl2br(esc($raffle->description)) ?>
                        </div>
                    <?php endif; ?>

                    <div class="stats-grid">
                        <div class="stat-box price">
                            <div class="stat-label">Preço por Número</div>
                            <div class="stat-value"><?= $raffle->getFormattedPrice() ?></div>
                        </div>
                        <div class="stat-box qty">
                            <div class="stat-label">Quantidade</div>
                            <div class="stat-value"><?= $raffle->quantity ?></div>
                        </div>
                        <div class="stat-box date">
                            <div class="stat-label">Data do Sorteio</div>
                            <div class="stat-value"><?= $raffle->getFormattedDrawDate() ?></div>
                        </div>
                        <div class="stat-box total">
                            <div class="stat-label">Valor Total</div>
                            <div class="stat-value"><?= $raffle->getFormattedTotalValue() ?></div>
                        </div>
                    </div>

                    <div class="meta-info">
                        <p><strong><i class="fas fa-calendar-plus mr-1"></i> Criada em:</strong> <?= $raffle->created_at->format('d/m/Y H:i') ?></p>
                        <p><strong><i class="fas fa-sync mr-1"></i> Última atualização:</strong> <?= $raffle->updated_at->format('d/m/Y H:i') ?></p>
                    </div>

                    <a href="<?= site_url('raffles') ?>" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Voltar para lista
                    </a>
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
            <p class="modal-card-title"><i class="fas fa-exclamation-triangle mr-2"></i> Confirmar Exclusão</p>
            <button class="delete" aria-label="close" onclick="closeModal()"></button>
        </header>
        <section class="modal-card-body">
            <p>Tem certeza que deseja excluir a rifa <strong><?= esc($raffle->name) ?></strong>?</p>
            <p class="has-text-danger mt-2"><strong>Esta ação não pode ser desfeita!</strong></p>
        </section>
        <footer class="modal-card-foot">
            <button class="button" onclick="closeModal()">Cancelar</button>
            <form action="<?= site_url('raffles/' . $raffle->id) ?>" method="post" style="display: inline;">
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
        modal.classList.add('is-active');
    }

    function closeModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('is-active');
    }

    document.querySelector('.modal-background').addEventListener('click', closeModal);
</script>

<?= $this->endSection() ?>
