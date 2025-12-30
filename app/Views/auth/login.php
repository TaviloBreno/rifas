<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Login<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="hero is-primary is-fullheight-with-navbar">
    <div class="hero-body">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-5-tablet is-4-desktop">
                    <div class="box">
                        <div class="has-text-centered mb-5">
                            <span class="icon is-large has-text-primary">
                                <i class="fas fa-ticket-alt fa-3x"></i>
                            </span>
                            <h1 class="title is-4 mt-3">Entrar no Sistema</h1>
                            <p class="subtitle is-6 has-text-grey">Faça login para continuar</p>
                        </div>

                        <?php if (session()->has('error')): ?>
                            <div class="notification is-danger is-light">
                                <button class="delete"></button>
                                <?= session('error') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->has('success')): ?>
                            <div class="notification is-success is-light">
                                <button class="delete"></button>
                                <?= session('success') ?>
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

                        <form action="<?= site_url('login') ?>" method="post">
                            <?= csrf_field() ?>

                            <div class="field">
                                <label class="label">E-mail</label>
                                <div class="control has-icons-left">
                                    <input class="input" 
                                           type="email" 
                                           name="email" 
                                           placeholder="seu@email.com"
                                           value="<?= old('email') ?>"
                                           required
                                           autofocus>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Senha</label>
                                <div class="control has-icons-left">
                                    <input class="input" 
                                           type="password" 
                                           name="password" 
                                           placeholder="••••••••"
                                           required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="field">
                                <label class="checkbox">
                                    <input type="checkbox" name="remember" value="1">
                                    Lembrar-me
                                </label>
                            </div>

                            <div class="field">
                                <button type="submit" class="button is-primary is-fullwidth">
                                    <span class="icon">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </span>
                                    <span>Entrar</span>
                                </button>
                            </div>
                        </form>

                        <hr>

                        <div class="has-text-centered">
                            <p class="mb-2">
                                <a href="<?= site_url('forgot-password') ?>" class="has-text-link">
                                    <i class="fas fa-key"></i> Esqueceu sua senha?
                                </a>
                            </p>
                            <p>
                                Não tem uma conta? 
                                <a href="<?= site_url('register') ?>" class="has-text-link has-text-weight-bold">
                                    Cadastre-se
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="has-text-centered mt-4">
                        <p class="is-size-7 has-text-white">
                            <i class="fas fa-info-circle"></i> 
                            Demo: admin@rifas.com / admin123
                        </p>
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
