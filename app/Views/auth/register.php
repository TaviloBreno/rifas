<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Criar Conta<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="hero is-primary is-fullheight-with-navbar">
    <div class="hero-body">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-6-tablet is-5-desktop">
                    <div class="box">
                        <div class="has-text-centered mb-5">
                            <span class="icon is-large has-text-primary">
                                <i class="fas fa-user-plus fa-3x"></i>
                            </span>
                            <h1 class="title is-4 mt-3">Criar Conta</h1>
                            <p class="subtitle is-6 has-text-grey">Preencha os dados para se cadastrar</p>
                        </div>

                        <?php if (session()->has('error')): ?>
                            <div class="notification is-danger is-light">
                                <button class="delete"></button>
                                <?= session('error') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->has('errors')): ?>
                            <div class="notification is-danger is-light">
                                <button class="delete"></button>
                                <ul>
                                    <?php foreach (session('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="<?= site_url('register') ?>" method="post">
                            <?= csrf_field() ?>

                            <div class="field">
                                <label class="label">Nome Completo *</label>
                                <div class="control has-icons-left">
                                    <input class="input <?= session('errors.name') ? 'is-danger' : '' ?>" 
                                           type="text" 
                                           name="name" 
                                           placeholder="Seu nome completo"
                                           value="<?= old('name') ?>"
                                           required
                                           autofocus>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                                <?php if (session('errors.name')): ?>
                                    <p class="help is-danger"><?= session('errors.name') ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="field">
                                <label class="label">E-mail *</label>
                                <div class="control has-icons-left">
                                    <input class="input <?= session('errors.email') ? 'is-danger' : '' ?>" 
                                           type="email" 
                                           name="email" 
                                           placeholder="seu@email.com"
                                           value="<?= old('email') ?>"
                                           required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                                <?php if (session('errors.email')): ?>
                                    <p class="help is-danger"><?= session('errors.email') ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="field">
                                <label class="label">Telefone</label>
                                <div class="control has-icons-left">
                                    <input class="input" 
                                           type="tel" 
                                           name="phone" 
                                           placeholder="(00) 00000-0000"
                                           value="<?= old('phone') ?>">
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <div class="field">
                                        <label class="label">Senha *</label>
                                        <div class="control has-icons-left">
                                            <input class="input <?= session('errors.password') ? 'is-danger' : '' ?>" 
                                                   type="password" 
                                                   name="password" 
                                                   placeholder="Mínimo 6 caracteres"
                                                   required>
                                            <span class="icon is-small is-left">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                        </div>
                                        <?php if (session('errors.password')): ?>
                                            <p class="help is-danger"><?= session('errors.password') ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="field">
                                        <label class="label">Confirmar Senha *</label>
                                        <div class="control has-icons-left">
                                            <input class="input <?= session('errors.password_confirm') ? 'is-danger' : '' ?>" 
                                                   type="password" 
                                                   name="password_confirm" 
                                                   placeholder="Repita a senha"
                                                   required>
                                            <span class="icon is-small is-left">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                        </div>
                                        <?php if (session('errors.password_confirm')): ?>
                                            <p class="help is-danger"><?= session('errors.password_confirm') ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="field">
                                <label class="checkbox">
                                    <input type="checkbox" name="terms" required>
                                    Li e aceito os <a href="#" class="has-text-link">termos de uso</a>
                                </label>
                            </div>

                            <div class="field">
                                <button type="submit" class="button is-primary is-fullwidth">
                                    <span class="icon">
                                        <i class="fas fa-user-plus"></i>
                                    </span>
                                    <span>Criar Conta</span>
                                </button>
                            </div>
                        </form>

                        <hr>

                        <div class="has-text-centered">
                            <p>
                                Já tem uma conta? 
                                <a href="<?= site_url('login') ?>" class="has-text-link has-text-weight-bold">
                                    Faça login
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.querySelectorAll('.notification .delete').forEach(($delete) => {
        const $notification = $delete.parentNode;
        $delete.addEventListener('click', () => {
            $notification.parentNode.removeChild($notification);
        });
    });
</script>

<?= $this->endSection() ?>
