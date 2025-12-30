<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - Sistema de Rifas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero.is-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
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
    </style>
</head>
<body>
    <nav class="navbar is-primary" role="navigation" aria-label="main navigation">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item" href="<?= site_url('/') ?>">
                    <i class="fas fa-ticket-alt mr-2"></i> Rifas
                </a>
                <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarMain">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>
            <div id="navbarMain" class="navbar-menu">
                <div class="navbar-start">
                    <a class="navbar-item" href="<?= site_url('raffles') ?>">
                        <i class="fas fa-list mr-1"></i> Minhas Rifas
                    </a>
                    <a class="navbar-item" href="<?= site_url('raffles/new') ?>">
                        <i class="fas fa-plus mr-1"></i> Nova Rifa
                    </a>
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
                <strong>Sistema de Rifas</strong> - <?= date('Y') ?>
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
</body>
</html>
