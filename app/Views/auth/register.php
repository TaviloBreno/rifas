<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Criar Conta<?= $this->endSection() ?>

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
        max-width: 550px;
        margin: 0 auto;
    }
    
    .auth-logo {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
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
        margin-bottom: 1.25rem;
    }
    
    .auth-field label {
        display: block;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
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
        padding: 0.875rem 1rem 0.875rem 3rem;
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
    
    .auth-input.error {
        border-color: #dc3545;
    }
    
    .auth-input-wrapper:focus-within i {
        color: #667eea;
    }
    
    .field-error {
        color: #dc3545;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }
    
    .field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .auth-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        font-size: 0.875rem;
    }
    
    .auth-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #667eea;
    }
    
    .auth-checkbox a {
        color: #667eea;
        text-decoration: none;
    }
    
    .auth-checkbox a:hover {
        text-decoration: underline;
    }
    
    .auth-submit {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
        margin-top: 1.5rem;
    }
    
    .auth-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(40, 167, 69, 0.4);
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
        font-weight: 700;
        transition: color 0.3s ease;
    }
    
    .auth-links a:hover {
        color: #764ba2;
        text-decoration: underline;
    }
    
    .benefits-list {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1rem 1.5rem;
        margin-top: 2rem;
    }
    
    .benefits-list h4 {
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 0.75rem;
        font-size: 0.875rem;
    }
    
    .benefits-list ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .benefits-list li {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0;
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    .benefits-list li i {
        color: #28a745;
    }
    
    @media (max-width: 768px) {
        .auth-card {
            margin: 0 1rem;
            padding: 2rem;
        }
        
        .field-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<section class="auth-section">
    <div class="container">
        <div class="auth-card">
            <div class="auth-logo">
                <i class="fas fa-user-plus"></i>
            </div>
            
            <h1 class="auth-title">Crie sua Conta Grátis</h1>
            <p class="auth-subtitle">Participe das melhores rifas e concorra a prêmios incríveis</p>

            <?php if (session()->has('error')): ?>
                <div class="auth-notification error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= session('error') ?></span>
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

            <form action="<?= site_url('register') ?>" method="post">
                <?= csrf_field() ?>

                <div class="auth-field">
                    <label for="name">Nome Completo *</label>
                    <div class="auth-input-wrapper">
                        <input class="auth-input <?= session('errors.name') ? 'error' : '' ?>" 
                               type="text" 
                               id="name"
                               name="name" 
                               placeholder="Seu nome completo"
                               value="<?= old('name') ?>"
                               required
                               autofocus>
                        <i class="fas fa-user"></i>
                    </div>
                    <?php if (session('errors.name')): ?>
                        <p class="field-error"><?= session('errors.name') ?></p>
                    <?php endif; ?>
                </div>

                <div class="field-row">
                    <div class="auth-field">
                        <label for="email">E-mail *</label>
                        <div class="auth-input-wrapper">
                            <input class="auth-input <?= session('errors.email') ? 'error' : '' ?>" 
                                   type="email" 
                                   id="email"
                                   name="email" 
                                   placeholder="seu@email.com"
                                   value="<?= old('email') ?>"
                                   required>
                            <i class="fas fa-envelope"></i>
                        </div>
                        <?php if (session('errors.email')): ?>
                            <p class="field-error"><?= session('errors.email') ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="auth-field">
                        <label for="phone">WhatsApp</label>
                        <div class="auth-input-wrapper">
                            <input class="auth-input" 
                                   type="tel" 
                                   id="phone"
                                   name="phone" 
                                   placeholder="(00) 00000-0000"
                                   value="<?= old('phone') ?>">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                    </div>
                </div>

                <div class="field-row">
                    <div class="auth-field">
                        <label for="password">Senha *</label>
                        <div class="auth-input-wrapper">
                            <input class="auth-input <?= session('errors.password') ? 'error' : '' ?>" 
                                   type="password" 
                                   id="password"
                                   name="password" 
                                   placeholder="Mínimo 6 caracteres"
                                   required>
                            <i class="fas fa-lock"></i>
                        </div>
                        <?php if (session('errors.password')): ?>
                            <p class="field-error"><?= session('errors.password') ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="auth-field">
                        <label for="password_confirm">Confirmar Senha *</label>
                        <div class="auth-input-wrapper">
                            <input class="auth-input <?= session('errors.password_confirm') ? 'error' : '' ?>" 
                                   type="password" 
                                   id="password_confirm"
                                   name="password_confirm" 
                                   placeholder="Repita a senha"
                                   required>
                            <i class="fas fa-lock"></i>
                        </div>
                        <?php if (session('errors.password_confirm')): ?>
                            <p class="field-error"><?= session('errors.password_confirm') ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="auth-field">
                    <label class="auth-checkbox">
                        <input type="checkbox" name="terms" required>
                        <span>Li e aceito os <a href="#">termos de uso</a> e <a href="#">política de privacidade</a></span>
                    </label>
                </div>

                <button type="submit" class="auth-submit">
                    <i class="fas fa-user-plus"></i>
                    Criar Minha Conta
                </button>
            </form>

            <div class="auth-divider">
                <span>ou</span>
            </div>

            <div class="auth-links">
                <p style="color: #6c757d;">
                    Já tem uma conta? 
                    <a href="<?= site_url('login') ?>">Faça login</a>
                </p>
            </div>

            <div class="benefits-list">
                <h4><i class="fas fa-gift"></i> Ao criar sua conta você terá:</h4>
                <ul>
                    <li><i class="fas fa-check"></i> Acesso a todas as rifas disponíveis</li>
                    <li><i class="fas fa-check"></i> Acompanhamento de seus números</li>
                    <li><i class="fas fa-check"></i> Notificações de resultados</li>
                    <li><i class="fas fa-check"></i> Histórico completo de compras</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<script>
// Máscara de telefone
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 11) value = value.slice(0, 11);
    if (value.length > 6) {
        value = `(${value.slice(0,2)}) ${value.slice(2,7)}-${value.slice(7)}`;
    } else if (value.length > 2) {
        value = `(${value.slice(0,2)}) ${value.slice(2)}`;
    }
    e.target.value = value;
});
</script>

<?= $this->endSection() ?>
