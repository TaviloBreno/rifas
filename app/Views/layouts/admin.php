<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - Admin Rifas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 250px;
        }
        body {
            min-height: 100vh;
        }
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: var(--sidebar-width);
            background: #363636;
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 1.5rem;
            background: #2b2b2b;
            text-align: center;
        }
        .sidebar-brand a {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .sidebar-menu {
            padding: 1rem 0;
        }
        .sidebar-menu a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: #b5b5b5;
            transition: all 0.2s;
        }
        .sidebar-menu a:hover,
        .sidebar-menu a.is-active {
            background: #4a4a4a;
            color: white;
        }
        .sidebar-menu a i {
            width: 25px;
            margin-right: 0.5rem;
        }
        .sidebar-menu .menu-label {
            padding: 0.75rem 1.5rem;
            color: #7a7a7a;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            background: #f5f5f5;
            min-height: 100vh;
        }
        .admin-header {
            background: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid #e5e5e5;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-body {
            padding: 2rem;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .stat-card .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        @media screen and (max-width: 1023px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
                z-index: 100;
            }
            .sidebar.is-active {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
        <?= $this->renderSection('styles') ?>
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <a href="<?= site_url('admin') ?>">
                    <i class="fas fa-ticket-alt"></i> Admin
                </a>
            </div>
            
            <nav class="sidebar-menu">
                <a href="<?= site_url('admin') ?>" class="<?= uri_string() === 'admin' ? 'is-active' : '' ?>">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                
                <p class="menu-label">Gestão</p>
                
                <a href="<?= site_url('admin/raffles') ?>" class="<?= str_starts_with(uri_string(), 'admin/raffles') ? 'is-active' : '' ?>">
                    <i class="fas fa-ticket-alt"></i> Rifas
                </a>
                <a href="<?= site_url('admin/orders') ?>" class="<?= str_starts_with(uri_string(), 'admin/orders') ? 'is-active' : '' ?>">
                    <i class="fas fa-shopping-cart"></i> Pedidos
                </a>
                <a href="<?= site_url('admin/transfers') ?>" class="<?= str_starts_with(uri_string(), 'admin/transfers') ? 'is-active' : '' ?>">
                    <i class="fas fa-money-bill-transfer"></i> Transferências
                </a>
                <a href="<?= site_url('admin/users') ?>" class="<?= str_starts_with(uri_string(), 'admin/users') ? 'is-active' : '' ?>">
                    <i class="fas fa-users"></i> Usuários
                </a>
                
                <p class="menu-label">Configurações</p>
                
                <a href="<?= site_url('admin/settings') ?>" class="<?= uri_string() === 'admin/settings' ? 'is-active' : '' ?>">
                    <i class="fas fa-cog"></i> Geral
                </a>
                <a href="<?= site_url('admin/settings/payment') ?>" class="<?= uri_string() === 'admin/settings/payment' ? 'is-active' : '' ?>">
                    <i class="fas fa-credit-card"></i> Pagamento
                </a>
                
                <p class="menu-label">Sistema</p>
                
                <a href="<?= site_url('/') ?>" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Ver Site
                </a>
                <a href="<?= site_url('logout') ?>">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="admin-header">
                <div>
                    <button class="button is-white is-hidden-desktop" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <span class="is-size-5"><?= $this->renderSection('page-title') ?></span>
                </div>
                <div class="is-flex is-align-items-center">
                    <span class="mr-3">
                        <i class="fas fa-user"></i> <?= esc(session()->get('user_name')) ?>
                    </span>
                    <span class="tag is-danger"><?= ucfirst(session()->get('user_role')) ?></span>
                </div>
            </header>

            <div class="admin-body">
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

                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('is-active');
        }
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
