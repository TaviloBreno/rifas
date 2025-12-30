<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Contato<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="hero is-info is-small">
    <div class="hero-body">
        <div class="container has-text-centered">
            <h1 class="title">
                <i class="fas fa-envelope"></i> Contato
            </h1>
            <p class="subtitle">Entre em contato conosco</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-6">
                <div class="box">
                    <h2 class="title is-4 has-text-centered">
                        <i class="fas fa-headset"></i> Fale Conosco
                    </h2>
                    
                    <div class="content">
                        <div class="notification is-light">
                            <p class="is-size-5">
                                <i class="fas fa-envelope has-text-info"></i> 
                                <strong>E-mail:</strong><br>
                                <a href="mailto:<?= esc($settings['contact_email'] ?? 'contato@rifas.com') ?>">
                                    <?= esc($settings['contact_email'] ?? 'contato@rifas.com') ?>
                                </a>
                            </p>
                        </div>
                        
                        <div class="notification is-light">
                            <p class="is-size-5">
                                <i class="fab fa-whatsapp has-text-success"></i> 
                                <strong>WhatsApp:</strong><br>
                                <a href="https://wa.me/55<?= preg_replace('/\D/', '', $settings['contact_phone'] ?? '') ?>" target="_blank">
                                    <?= esc($settings['contact_phone'] ?? '(11) 99999-9999') ?>
                                </a>
                            </p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h3 class="title is-5">
                        <i class="fas fa-question-circle"></i> Perguntas Frequentes
                    </h3>
                    
                    <div class="content">
                        <details class="mb-3">
                            <summary class="has-text-weight-bold">Como funciona a compra de números?</summary>
                            <p class="mt-2 pl-4">
                                Escolha uma rifa, selecione os números desejados, preencha seus dados e pague via PIX.
                                Após a confirmação do pagamento, seus números serão reservados.
                            </p>
                        </details>
                        
                        <details class="mb-3">
                            <summary class="has-text-weight-bold">Como sei se ganhei?</summary>
                            <p class="mt-2 pl-4">
                                Você pode acompanhar o resultado na página da rifa ou consultar seus pedidos
                                pelo e-mail cadastrado. Também entramos em contato com os ganhadores via WhatsApp.
                            </p>
                        </details>
                        
                        <details class="mb-3">
                            <summary class="has-text-weight-bold">O pagamento é seguro?</summary>
                            <p class="mt-2 pl-4">
                                Sim! Utilizamos PIX, que é um meio de pagamento oficial do Banco Central,
                                rápido e seguro. A confirmação é praticamente instantânea.
                            </p>
                        </details>
                        
                        <details class="mb-3">
                            <summary class="has-text-weight-bold">Posso escolher meus números?</summary>
                            <p class="mt-2 pl-4">
                                Sim! Você pode escolher seus números da sorte manualmente ou usar
                                a opção de seleção aleatória.
                            </p>
                        </details>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
