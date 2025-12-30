<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Login<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
    .auth-section {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
        margin-top: -2rem;
        padding: 4rem 0;
    }
    
    .auth-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .auth-card {
        background: white;
        border-radius: 25px;
        padding: 3rem;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
        position: relative;
        z-index: 10;
        max-width: 450px;
        margin: 0 auto;
    }
    
    .auth-logo {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }
    
    .auth-logo i {
        font-size: 2.5rem;
        color: white;
    }
    
    .auth-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1a1a2e;
        text-align: center;
        margin-bottom: 0.5rem;
    }
    
    .auth-subtitle {
        color: #6c757d;
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .auth-notification {
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .auth-notification.error {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        color: #721c24;
        border-left: 4px solid #dc3545;
    }
    
    .auth-notification.success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
        border-left: 4px solid #28a745;
    }
    
    .auth-notification .close-btn {
        background: none;
        border: none;
        font-size: 1.25rem;
        cursor: pointer;
        opacity: 0.5;
        margin-left: auto;
    }
    
    .auth-notification .close-btn:hover {
        opacity: 1;
    }
    
    .auth-field {
        margin-bottom: 1.5rem;
    }
    
    .auth-field label {
        display: block;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0.5rem;
    }
    
    .auth-input-wrapper {
        position: relative;
    }
    
    .auth-input-wrapper i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        transition: color 0.3s ease;
    }
    
    .auth-input {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .auth-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }
    
    .auth-input:focus + i,
    .auth-input-wrapper:focus-within i {
        color: #667eea;
    }
    
    .auth-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }
    
    .auth-checkbox input[type="checkbox"] {
        width: 20px;
        height: 20px;
        accent-color: #667eea;
    }
    
    .auth-submit {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1.125rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .auth-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    }
    
    .auth-divider {
        display: flex;
        align-items: center;
        margin: 2rem 0;
    }
    
    .auth-divider::before,
    .auth-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e0e0e0;
    }
    
    .auth-divider span {
        padding: 0 1rem;
        color: #6c757d;
        font-size: 0.875rem;
    }
    
    .auth-links {
        text-align: center;
    }
    
    .auth-links a {
        color: #667eea;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .auth-links a:hover {
        color: #764ba2;
        text-decoration: underline;
    }
    
    .auth-links p {
        margin-bottom: 0.75rem;
        color: #6c757d;
    }
    
    .demo-info {
        text-align: center;
        margin-top: 2rem;
        color: white;
        background: rgba(255, 255, 255, 0.1);
        padding: 1rem;
        border-radius: 12px;
        backdrop-filter: blur(10px);
    }
    
    .demo-info code {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.25rem 0.5rem;
        border-radius: 5px;
        font-size: 0.875rem;
    }
    
    @media (max-width: 768px) {
        .auth-card {
            margin: 0 1rem;
            padding: 2rem;
        }
    }
</style>

<section class="auth-section">
    <div class="container">
        <div class="auth-card">
            <div class="auth-logo">
                <i class="fas fa-ticket-alt"></i>
            </div>
            
            <h1 class="auth-title">Bem-vindo de volta!</h1>
            <p class="auth-subtitle">Faça login para acessar sua conta</p>

            <?php if (session()->has('error')): ?>
                <div class="auth-notification error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= session('error') ?></span>
                    <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
                </div>
            <?php endif; ?>

            <?php if (session()->has('success')): ?>
                <div class="auth-notification success">
                    <i class="fas fa-check-circle"></i>
                    <span><?= session('success') ?></span>
                    <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
                </div>
            <?php endif; ?>

            <?php if (session()->has('errors')): ?>
                <div class="auth-notification error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <?php foreach (session('errors') as $error): ?>
                            <p style="margin: 0;"><?= esc($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                    <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('login') ?>" method="post">
                <?= csrf_field() ?>

                <div class="auth-field">
                    <label for="email">E-mail</label>
                    <div class="auth-input-wrapper">
                        <input class="auth-input" 
                               type="email" 
                               id="email"
                               name="email" 
                               placeholder="seu@email.com"
                               value="<?= old('email') ?>"
                               required
                               autofocus>
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>

                <div class="auth-field">
                    <label for="password">Senha</label>
                    <div class="auth-input-wrapper">
                        <input class="auth-input" 
                               type="password" 
                               id="password"
                               name="password" 
                               placeholder="••••••••"
                               required>
                        <i class="fas fa-lock"></i>
                    </div>
                </div>

                <div class="auth-field">
                    <label class="auth-checkbox">
                        <input type="checkbox" name="remember" value="1">
                        <span>Lembrar-me neste dispositivo</span>
                    </label>
                </div>

                <button type="submit" class="auth-submit">
                    <i class="fas fa-sign-in-alt"></i>
                    Entrar
                </button>
            </form>

            <div class="auth-divider">
                <span>ou</span>
            </div>

            <div class="auth-links">
                <p>
                    <a href="<?= site_url('forgot-password') ?>">
                        <i class="fas fa-key"></i> Esqueceu sua senha?
                    </a>
                </p>
                <p>
                    Não tem uma conta? 
                    <a href="<?= site_url('register') ?>" style="font-weight: 700;">
                        Cadastre-se grátis
                    </a>
                </p>
            </div>
        </div>

        <div class="demo-info">
            <p><i class="fas fa-info-circle"></i> <strong>Demo:</strong></p>
            <p><code>admin@rifas.com</code> / <code>admin123</code></p>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
