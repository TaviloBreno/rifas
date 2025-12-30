<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Contato<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
    .contact-hero {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        padding: 6rem 0 4rem;
        margin-top: -2rem;
        position: relative;
        text-align: center;
        color: white;
    }
    
    .contact-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .contact-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        backdrop-filter: blur(10px);
        animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .contact-icon i {
        font-size: 2.5rem;
    }
    
    .contact-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }
    
    .contact-subtitle {
        opacity: 0.9;
    }
    
    .contact-main {
        margin-top: -3rem;
        position: relative;
        z-index: 10;
        padding-bottom: 3rem;
    }
    
    .contact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        max-width: 900px;
        margin: 0 auto 3rem;
    }
    
    .contact-card {
        background: white;
        border-radius: 25px;
        padding: 2rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .contact-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.15);
    }
    
    .card-icon {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }
    
    .card-icon.email-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .card-icon.whatsapp-icon {
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
    }
    
    .card-icon i {
        font-size: 1.75rem;
        color: white;
    }
    
    .contact-card h3 {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 0.5rem;
    }
    
    .contact-card p {
        color: #6c757d;
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }
    
    .contact-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.75rem;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .contact-link.email-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .contact-link.whatsapp-link {
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        color: white;
    }
    
    .contact-link:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        color: white;
    }
    
    .faq-section {
        background: white;
        border-radius: 25px;
        padding: 2.5rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        max-width: 800px;
        margin: 0 auto;
    }
    
    .faq-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .faq-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }
    
    .faq-icon i {
        font-size: 1.5rem;
        color: white;
    }
    
    .faq-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a2e;
    }
    
    .faq-item {
        border: 2px solid #f0f0f0;
        border-radius: 15px;
        margin-bottom: 1rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .faq-item:hover {
        border-color: #667eea;
    }
    
    .faq-question {
        padding: 1.25rem 1.5rem;
        font-weight: 700;
        color: #1a1a2e;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 1rem;
        background: #fafafa;
        transition: all 0.3s ease;
    }
    
    .faq-question:hover {
        background: #f0f0f0;
    }
    
    .faq-question .icon-wrap {
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .faq-question .icon-wrap i {
        color: white;
        font-size: 0.875rem;
    }
    
    .faq-question .arrow {
        margin-left: auto;
        color: #6c757d;
        transition: transform 0.3s ease;
    }
    
    .faq-item[open] .faq-question .arrow {
        transform: rotate(180deg);
    }
    
    .faq-answer {
        padding: 1.25rem 1.5rem 1.25rem 4.5rem;
        color: #495057;
        line-height: 1.7;
        background: white;
    }
    
    .help-box {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        margin-top: 2rem;
    }
    
    .help-box p {
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    
    .help-box strong {
        color: #1a1a2e;
    }
    
    @media (max-width: 768px) {
        .contact-title {
            font-size: 1.5rem;
        }
        
        .contact-grid {
            grid-template-columns: 1fr;
        }
        
        .faq-answer {
            padding-left: 1.5rem;
        }
    }
</style>

<!-- Hero Section -->
<section class="contact-hero">
    <div class="container">
        <div class="contact-icon">
            <i class="fas fa-headset"></i>
        </div>
        <h1 class="contact-title">Fale Conosco</h1>
        <p class="contact-subtitle">Estamos aqui para ajudar você!</p>
    </div>
</section>

<!-- Main Content -->
<section class="section contact-main">
    <div class="container">
        
        <!-- Contact Cards -->
        <div class="contact-grid">
            <div class="contact-card">
                <div class="card-icon email-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3>E-mail</h3>
                <p>Envie sua dúvida e responderemos em até 24 horas</p>
                <a href="mailto:<?= esc($settings['contact_email'] ?? 'contato@rifas.com') ?>" class="contact-link email-link">
                    <i class="fas fa-paper-plane"></i>
                    <?= esc($settings['contact_email'] ?? 'contato@rifas.com') ?>
                </a>
            </div>
            
            <div class="contact-card">
                <div class="card-icon whatsapp-icon">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <h3>WhatsApp</h3>
                <p>Atendimento rápido de segunda a sexta</p>
                <a href="https://wa.me/55<?= preg_replace('/\D/', '', $settings['contact_phone'] ?? '11999999999') ?>" target="_blank" class="contact-link whatsapp-link">
                    <i class="fab fa-whatsapp"></i>
                    <?= esc($settings['contact_phone'] ?? '(11) 99999-9999') ?>
                </a>
            </div>
        </div>
        
        <!-- FAQ Section -->
        <div class="faq-section">
            <div class="faq-header">
                <div class="faq-icon">
                    <i class="fas fa-question"></i>
                </div>
                <h2>Perguntas Frequentes</h2>
            </div>
            
            <details class="faq-item">
                <summary class="faq-question">
                    <span class="icon-wrap"><i class="fas fa-shopping-cart"></i></span>
                    <span>Como funciona a compra de números?</span>
                    <i class="fas fa-chevron-down arrow"></i>
                </summary>
                <div class="faq-answer">
                    É muito simples! Escolha uma rifa, selecione os números desejados (você pode escolher manualmente ou usar o sorteio aleatório), preencha seus dados e pague via PIX. Após a confirmação do pagamento, seus números serão automaticamente reservados no seu nome.
                </div>
            </details>
            
            <details class="faq-item">
                <summary class="faq-question">
                    <span class="icon-wrap"><i class="fas fa-trophy"></i></span>
                    <span>Como sei se ganhei?</span>
                    <i class="fas fa-chevron-down arrow"></i>
                </summary>
                <div class="faq-answer">
                    Você pode acompanhar o resultado diretamente na página da rifa ou consultar seus pedidos pelo e-mail cadastrado. Também entramos em contato com os ganhadores via WhatsApp para combinar a entrega do prêmio. Fique de olho!
                </div>
            </details>
            
            <details class="faq-item">
                <summary class="faq-question">
                    <span class="icon-wrap"><i class="fas fa-shield-alt"></i></span>
                    <span>O pagamento é seguro?</span>
                    <i class="fas fa-chevron-down arrow"></i>
                </summary>
                <div class="faq-answer">
                    Com certeza! Utilizamos exclusivamente PIX, que é um meio de pagamento oficial do Banco Central do Brasil, extremamente rápido e seguro. A confirmação do pagamento é praticamente instantânea, sem taxas adicionais.
                </div>
            </details>
            
            <details class="faq-item">
                <summary class="faq-question">
                    <span class="icon-wrap"><i class="fas fa-hand-pointer"></i></span>
                    <span>Posso escolher meus números da sorte?</span>
                    <i class="fas fa-chevron-down arrow"></i>
                </summary>
                <div class="faq-answer">
                    Sim! Você tem total liberdade para escolher seus números da sorte manualmente, um por um. Também oferecemos a opção de seleção aleatória para quem prefere deixar o destino decidir. É você quem manda!
                </div>
            </details>
            
            <details class="faq-item">
                <summary class="faq-question">
                    <span class="icon-wrap"><i class="fas fa-clock"></i></span>
                    <span>Quanto tempo tenho para pagar?</span>
                    <i class="fas fa-chevron-down arrow"></i>
                </summary>
                <div class="faq-answer">
                    Após selecionar seus números, você tem até 15 minutos para efetuar o pagamento via PIX. Caso o tempo expire, os números são liberados novamente para outros participantes. Fique tranquilo, o processo é rápido!
                </div>
            </details>
            
            <div class="help-box">
                <p>Não encontrou o que procurava?</p>
                <strong>Entre em contato pelo WhatsApp que teremos prazer em ajudar!</strong>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
