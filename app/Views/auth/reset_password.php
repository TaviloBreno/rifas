<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Redefinir Senha<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="hero is-primary is-fullheight-with-navbar">
    <div class="hero-body">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-5-tablet is-4-desktop">
                    <div class="box">
                        <div class="has-text-centered mb-5">
                            <span class="icon is-large has-text-success">
                                <i class="fas fa-lock-open fa-3x"></i>
                            </span>
                            <h1 class="title is-4 mt-3">Redefinir Senha</h1>
                            <p class="subtitle is-6 has-text-grey">
                                Digite sua nova senha
                            </p>
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

                        <form action="<?= site_url('reset-password') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="token" value="<?= esc($token) ?>">

                            <div class="field">
                                <label class="label">Nova Senha</label>
                                <div class="control has-icons-left">
                                    <input class="input <?= session('errors.password') ? 'is-danger' : '' ?>" 
                                           type="password" 
                                           name="password" 
                                           placeholder="Mínimo 6 caracteres"
                                           required
                                           autofocus>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                                <?php if (session('errors.password')): ?>
                                    <p class="help is-danger"><?= session('errors.password') ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="field">
                                <label class="label">Confirmar Nova Senha</label>
                                <div class="control has-icons-left">
                                    <input class="input <?= session('errors.password_confirm') ? 'is-danger' : '' ?>" 
                                           type="password" 
                                           name="password_confirm" 
                                           placeholder="Repita a nova senha"
                                           required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                                <?php if (session('errors.password_confirm')): ?>
                                    <p class="help is-danger"><?= session('errors.password_confirm') ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="field">
                                <button type="submit" class="button is-success is-fullwidth">
                                    <span class="icon">
                                        <i class="fas fa-save"></i>
                                    </span>
                                    <span>Salvar Nova Senha</span>
                                </button>
                            </div>
                        </form>

                        <hr>

                        <div class="has-text-centered">
                            <p>
                                <a href="<?= site_url('login') ?>" class="has-text-link">
                                    <i class="fas fa-arrow-left"></i> Voltar ao login
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
