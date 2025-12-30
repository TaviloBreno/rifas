<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - <?= $settings['site_name'] ?? 'Sistema de Rifas' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .hero.is-primary, .navbar.is-primary {
            background: var(--primary-gradient);
        }
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
        .raffle-image {
            height: 200px;
            object-fit: cover;
        }
        .navbar-brand .navbar-item {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .number-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
            gap: 8px;
        }
        .number-item {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
        }
        .number-item.available {
            background-color: #48c774;
            color: white;
        }
        .number-item.available:hover {
            background-color: #3ec46d;
            transform: scale(1.05);
        }
        .number-item.reserved {
            background-color: #ffdd57;
            color: #333;
            cursor: not-allowed;
        }
        .number-item.sold {
            background-color: #f14668;
            color: white;
            cursor: not-allowed;
        }
        .number-item.selected {
            border-color: #3273dc;
            box-shadow: 0 0 0 3px rgba(50, 115, 220, 0.3);
        }
        .sticky-cart {
            position: sticky;
            top: 20px;
        }
        .footer {
            background-color: #363636;
            color: #f5f5f5;
        }
        .footer a {
            color: #48c774;
        }
        .stats-box {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .progress-container {
            margin-top: 1rem;
        }
        .winner-badge {
            background: linear-gradient(135deg, #f5af19 0%, #f12711 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
        }
        <?= $this->renderSection('styles') ?>
    </style>
</head>
<body>
    <nav class="navbar is-primary" role="navigation" aria-label="main navigation">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item" href="<?= site_url('/') ?>">
                    <i class="fas fa-ticket-alt mr-2"></i> <?= $settings['site_name'] ?? 'Rifas' ?>
                </a>
                <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarMain">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>
            <div id="navbarMain" class="navbar-menu">
                <div class="navbar-start">
                    <a class="navbar-item" href="<?= site_url('/') ?>">
                        <i class="fas fa-home mr-1"></i> Início
                    </a>
                    <a class="navbar-item" href="<?= site_url('ganhadores') ?>">
                        <i class="fas fa-trophy mr-1"></i> Ganhadores
                    </a>
                    <a class="navbar-item" href="<?= site_url('meus-pedidos') ?>">
                        <i class="fas fa-receipt mr-1"></i> Meus Pedidos
                    </a>
                </div>
                <div class="navbar-end">
                    <?php if (session()->get('logged_in')): ?>
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link">
                                <span class="icon">
                                    <i class="fas fa-user-circle"></i>
                                </span>
                                <span><?= esc(session()->get('user_name')) ?></span>
                            </a>
                            <div class="navbar-dropdown is-right">
                                <?php if (session()->get('user_role') === 'admin' || session()->get('user_role') === 'funcionario'): ?>
                                    <a class="navbar-item" href="<?= site_url('admin') ?>">
                                        <span class="icon has-text-info">
                                            <i class="fas fa-cog"></i>
                                        </span>
                                        <span>Painel Admin</span>
                                    </a>
                                    <hr class="navbar-divider">
                                <?php endif; ?>
                                <div class="navbar-item">
                                    <span class="tag <?= session()->get('user_role') === 'admin' ? 'is-danger' : (session()->get('user_role') === 'funcionario' ? 'is-warning' : 'is-info') ?>">
                                        <?= ucfirst(session()->get('user_role')) ?>
                                    </span>
                                </div>
                                <hr class="navbar-divider">
                                <a class="navbar-item" href="<?= site_url('logout') ?>">
                                    <span class="icon has-text-danger">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </span>
                                    <span>Sair</span>
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="navbar-item">
                            <div class="buttons">
                                <a class="button is-light" href="<?= site_url('login') ?>">
                                    <span class="icon">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </span>
                                    <span>Entrar</span>
                                </a>
                                <a class="button is-success" href="<?= site_url('register') ?>">
                                    <span class="icon">
                                        <i class="fas fa-user-plus"></i>
                                    </span>
                                    <span>Cadastrar</span>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <?php if (session()->getFlashdata('success')): ?>
        <section class="section py-3">
            <div class="container">
                <div class="notification is-success is-light">
                    <button class="delete"></button>
                    <?= session()->getFlashdata('success') ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <section class="section py-3">
            <div class="container">
                <div class="notification is-danger is-light">
                    <button class="delete"></button>
                    <?= session()->getFlashdata('error') ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>

    <footer class="footer">
        <div class="content has-text-centered">
            <p>
                <strong><?= $settings['site_name'] ?? 'Sistema de Rifas' ?></strong> - <?= date('Y') ?>
            </p>
            <p>
                <a href="<?= site_url('contato') ?>">Contato</a> | 
                <a href="<?= site_url('ganhadores') ?>">Ganhadores</a> | 
                <a href="<?= site_url('meus-pedidos') ?>">Meus Pedidos</a>
            </p>
        </div>
    </footer>

    <script>
        // Navbar burger menu toggle
        document.addEventListener('DOMContentLoaded', () => {
            const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
            $navbarBurgers.forEach(el => {
                el.addEventListener('click', () => {
                    const target = el.dataset.target;
                    const $target = document.getElementById(target);
                    el.classList.toggle('is-active');
                    $target.classList.toggle('is-active');
                });
            });

            // Delete notification
            (document.querySelectorAll('.notification .delete') || []).forEach(($delete) => {
                const $notification = $delete.parentNode;
                $delete.addEventListener('click', () => {
                    $notification.parentNode.removeChild($notification);
                });
            });
        });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>