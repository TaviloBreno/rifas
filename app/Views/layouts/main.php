<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - <?= $settings['site_name'] ?? 'Sistema de Rifas' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gold-gradient: linear-gradient(135deg, #f5af19 0%, #f12711 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --dark-gradient: linear-gradient(135deg, #232526 0%, #414345 100%);
        }
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
        }
        
        .navbar {
            background: rgba(255,255,255,0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        
        .navbar-item, .navbar-link {
            color: #363636 !important;
            font-weight: 500;
        }
        
        .navbar-item:hover {
            background-color: rgba(102, 126, 234, 0.1) !important;
            color: #667eea !important;
        }
        
        .navbar-brand .navbar-item {
            font-weight: 700;
            font-size: 1.5rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero-main {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%), 
                        url('https://images.unsplash.com/photo-1513151233558-d860c5398176?w=1920&q=80') center/cover;
            min-height: 500px;
        }
        
        .hero-main .title {
            font-weight: 800;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .hero-main .subtitle {
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }
        
        .card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
            border-radius: 16px;
            overflow: hidden;
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        .card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .card-image {
            position: relative;
            overflow: hidden;
        }
        
        .card-image::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60%;
            background: linear-gradient(to top, rgba(0,0,0,0.5), transparent);
            pointer-events: none;
        }
        
        .raffle-image {
            height: 220px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .card:hover .raffle-image {
            transform: scale(1.1);
        }
        
        .card-content {
            padding: 1.5rem;
        }
        
        .card-content .title {
            font-weight: 700;
            color: #2d3748;
        }
        
        .price-tag {
            background: var(--success-gradient);
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(17, 153, 142, 0.4);
        }
        
        .featured-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--gold-gradient);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            z-index: 10;
            box-shadow: 0 4px 15px rgba(245, 175, 25, 0.5);
        }
        
        .number-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(55px, 1fr));
            gap: 8px;
            max-height: 400px;
            overflow-y: auto;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 12px;
        }
        
        .number-item {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid transparent;
            font-size: 0.9rem;
        }
        
        .number-item.available {
            background: linear-gradient(145deg, #48c774, #3ec46d);
            color: white;
            box-shadow: 0 3px 10px rgba(72, 199, 116, 0.3);
        }
        
        .number-item.available:hover {
            transform: scale(1.15);
            box-shadow: 0 6px 20px rgba(72, 199, 116, 0.5);
        }
        
        .number-item.reserved {
            background: linear-gradient(145deg, #ffdd57, #ffd633);
            color: #333;
            cursor: not-allowed;
        }
        
        .number-item.sold {
            background: linear-gradient(145deg, #f14668, #e8365d);
            color: white;
            cursor: not-allowed;
        }
        
        .number-item.selected {
            border-color: #3273dc;
            box-shadow: 0 0 0 4px rgba(50, 115, 220, 0.4);
            transform: scale(1.1);
        }
        
        .sticky-cart {
            position: sticky;
            top: 100px;
        }
        
        .cart-box {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            border: 2px solid #f0f0f0;
        }
        
        .footer {
            background: var(--dark-gradient);
            color: #f5f5f5;
            padding: 3rem 1.5rem;
        }
        
        .footer a {
            color: #48c774;
            transition: color 0.3s;
        }
        
        .footer a:hover {
            color: #3ec46d;
        }
        
        .stats-box {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }
        
        .stats-box:hover {
            transform: translateY(-5px);
        }
        
        .winner-badge {
            background: var(--gold-gradient);
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 25px;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(245, 175, 25, 0.4);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .section-title {
            position: relative;
            display: inline-block;
            font-weight: 700;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: var(--primary-gradient);
            border-radius: 2px;
        }
        
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        }
        
        .btn-success {
            background: var(--success-gradient);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(17, 153, 142, 0.4);
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(17, 153, 142, 0.5);
        }
        
        .how-it-works-item {
            text-align: center;
            padding: 2rem;
        }
        
        .how-it-works-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        
        .how-it-works-icon i {
            font-size: 2rem;
            color: white;
        }
        
        .notification {
            border-radius: 12px;
        }
        
        .progress {
            border-radius: 10px;
            height: 12px;
        }
        
        .tag {
            border-radius: 20px;
            font-weight: 500;
        }
        
        /* Scrollbar personalizada */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #5a6fd6, #6a4190);
        }
        
        <?= $this->renderSection('styles') ?>
    </style>
</head>
<body>
    <nav class="navbar is-fixed-top" role="navigation" aria-label="main navigation">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item" href="<?= site_url('/') ?>">
                    <i class="fas fa-ticket-alt mr-2" style="color: #667eea;"></i> <?= $settings['site_name'] ?? 'Rifas' ?>
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
                    <a class="navbar-item" href="<?= site_url('rifas') ?>">
                        <i class="fas fa-ticket-alt mr-1"></i> Rifas
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
                                <?php if (session()->get('user_role') === 'admin' || session()->get('user_role') === 'operator'): ?>
                                    <a class="navbar-item" href="<?= site_url('admin') ?>">
                                        <span class="icon has-text-info">
                                            <i class="fas fa-cog"></i>
                                        </span>
                                        <span>Painel Admin</span>
                                    </a>
                                    <hr class="navbar-divider">
                                <?php endif; ?>
                                <div class="navbar-item">
                                    <span class="tag <?= session()->get('user_role') === 'admin' ? 'is-danger' : (session()->get('user_role') === 'operator' ? 'is-warning' : 'is-info') ?>">
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
                                <a class="button is-light is-rounded" href="<?= site_url('login') ?>">
                                    <span class="icon">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </span>
                                    <span>Entrar</span>
                                </a>
                                <a class="button btn-primary is-rounded" href="<?= site_url('register') ?>">
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

    <div style="padding-top: 52px;">
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
    </div>

    <footer class="footer">
        <div class="container">
            <div class="columns">
                <div class="column is-4">
                    <h3 class="title is-5 has-text-white">
                        <i class="fas fa-ticket-alt mr-2"></i><?= $settings['site_name'] ?? 'Rifas Online' ?>
                    </h3>
                    <p class="has-text-grey-light">
                        <?= $settings['site_description'] ?? 'A melhor plataforma de rifas online. Participe e concorra a prêmios incríveis!' ?>
                    </p>
                </div>
                <div class="column is-4">
                    <h3 class="title is-5 has-text-white">Links Rápidos</h3>
                    <ul>
                        <li><a href="<?= site_url('/') ?>"><i class="fas fa-angle-right mr-2"></i>Início</a></li>
                        <li><a href="<?= site_url('ganhadores') ?>"><i class="fas fa-angle-right mr-2"></i>Ganhadores</a></li>
                        <li><a href="<?= site_url('meus-pedidos') ?>"><i class="fas fa-angle-right mr-2"></i>Meus Pedidos</a></li>
                        <li><a href="<?= site_url('contato') ?>"><i class="fas fa-angle-right mr-2"></i>Contato</a></li>
                    </ul>
                </div>
                <div class="column is-4">
                    <h3 class="title is-5 has-text-white">Contato</h3>
                    <?php if (!empty($settings['contact_email'])): ?>
                        <p><i class="fas fa-envelope mr-2"></i><?= esc($settings['contact_email']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($settings['contact_phone'])): ?>
                        <p><i class="fab fa-whatsapp mr-2"></i><?= esc($settings['contact_phone']) ?></p>
                    <?php endif; ?>
                    <div class="mt-4">
                        <?php if (!empty($settings['social_facebook'])): ?>
                            <a href="<?= esc($settings['social_facebook']) ?>" class="mr-3" target="_blank"><i class="fab fa-facebook fa-lg"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($settings['social_instagram'])): ?>
                            <a href="<?= esc($settings['social_instagram']) ?>" class="mr-3" target="_blank"><i class="fab fa-instagram fa-lg"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($settings['social_youtube'])): ?>
                            <a href="<?= esc($settings['social_youtube']) ?>" class="mr-3" target="_blank"><i class="fab fa-youtube fa-lg"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <hr style="background-color: #4a4a4a;">
            <div class="content has-text-centered has-text-grey-light">
                <p>&copy; <?= date('Y') ?> <?= $settings['site_name'] ?? 'Rifas Online' ?>. Todos os direitos reservados.</p>
            </div>
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