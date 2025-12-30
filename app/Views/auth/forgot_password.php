<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Esqueci Minha Senha<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="hero is-primary is-fullheight-with-navbar">
    <div class="hero-body">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-5-tablet is-4-desktop">
                    <div class="box">
                        <div class="has-text-centered mb-5">
                            <span class="icon is-large has-text-warning">
                                <i class="fas fa-key fa-3x"></i>
                            </span>
                            <h1 class="title is-4 mt-3">Esqueci Minha Senha</h1>
                            <p class="subtitle is-6 has-text-grey">
                                Digite seu e-mail para receber o link de recuperação
                            </p>
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
                                <?= session('success', false) ?>
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

                        <form action="<?= site_url('forgot-password') ?>" method="post">
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
                                <button type="submit" class="button is-warning is-fullwidth">
                                    <span class="icon">
                                        <i class="fas fa-paper-plane"></i>
                                    </span>
                                    <span>Enviar Link de Recuperação</span>
                                </button>
                            </div>
                        </form>

                        <hr>

                        <div class="has-text-centered">
                            <p>
                                Lembrou sua senha? 
                                <a href="<?= site_url('login') ?>" class="has-text-link has-text-weight-bold">
                                    Voltar ao login
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
