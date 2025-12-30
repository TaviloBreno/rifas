<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Pagamento - Pedido #<?= $order->id ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-8">
                <div class="box has-text-centered">
                    <span class="icon is-large has-text-warning">
                        <i class="fas fa-3x fa-clock"></i>
                    </span>
                    
                    <h1 class="title is-3 mt-4">Aguardando Pagamento</h1>
                    <p class="subtitle">Pedido #<?= str_pad($order->id, 6, '0', STR_PAD_LEFT) ?></p>

                    <!-- Temporizador -->
                    <div class="notification is-warning is-light">
                        <p class="is-size-5">
                            <i class="fas fa-hourglass-half"></i> 
                            Tempo restante: <strong id="countdown"><?= $order->getRemainingTime() ?></strong>
                        </p>
                        <p class="is-size-7">Após esse tempo, os números serão liberados.</p>
                    </div>

                    <div class="columns is-centered mt-5">
                        <!-- QR Code PIX -->
                        <div class="column is-5">
                            <h3 class="title is-5">
                                <i class="fas fa-qrcode"></i> QR Code PIX
                            </h3>
                            <figure class="image is-square" style="max-width: 250px; margin: 0 auto;">
                                <img src="<?= esc($qrCodeUrl) ?>" alt="QR Code PIX" style="border-radius: 10px;">
                            </figure>
                            <p class="has-text-grey mt-2 is-size-7">
                                Escaneie com o app do seu banco
                            </p>
                        </div>

                        <!-- Copia e Cola -->
                        <div class="column is-7">
                            <h3 class="title is-5">
                                <i class="fas fa-copy"></i> PIX Copia e Cola
                            </h3>
                            <div class="field">
                                <div class="control">
                                    <textarea class="textarea is-small" id="pixCode" readonly rows="4"><?= esc($pixCode) ?></textarea>
                                </div>
                            </div>
                            <button class="button is-info is-fullwidth" onclick="copyPixCode()">
                                <span class="icon">
                                    <i class="fas fa-copy"></i>
                                </span>
                                <span>Copiar Código PIX</span>
                            </button>
                        </div>
                    </div>

                    <!-- Valor -->
                    <div class="notification is-success is-light mt-5">
                        <p class="is-size-4">
                            <strong>Valor a Pagar:</strong> 
                            <span class="has-text-success is-size-3"><?= $order->getFormattedAmount() ?></span>
                        </p>
                    </div>

                    <!-- Detalhes do Pedido -->
                    <div class="box has-background-light mt-4">
                        <h4 class="title is-6">Detalhes do Pedido</h4>
                        <div class="columns is-mobile">
                            <div class="column">
                                <p><strong>Rifa:</strong> <?= esc($order->raffle_name) ?></p>
                                <p><strong>Comprador:</strong> <?= esc($order->customer_name) ?></p>
                            </div>
                            <div class="column">
                                <p><strong>Quantidade:</strong> <?= $order->total_numbers ?> número(s)</p>
                                <p><strong>E-mail:</strong> <?= esc($order->customer_email) ?></p>
                            </div>
                        </div>
                        
                        <p class="mt-3"><strong>Números Reservados:</strong></p>
                        <div class="tags">
                            <?php foreach ($numbers as $number): ?>
                                <span class="tag is-info is-medium">
                                    <?= $number->getFormattedNumber($raffle->getNumberDigits()) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Instruções -->
                    <div class="content has-text-left mt-5">
                        <h4><i class="fas fa-info-circle"></i> Instruções:</h4>
                        <ol>
                            <li>Abra o app do seu banco</li>
                            <li>Escolha pagar via PIX</li>
                            <li>Escaneie o QR Code ou cole o código PIX</li>
                            <li>Confirme o pagamento</li>
                            <li>Aguarde a confirmação (geralmente é instantâneo)</li>
                        </ol>
                    </div>

                    <div class="notification is-info is-light">
                        <p>
                            <i class="fas fa-info-circle"></i> 
                            Após o pagamento, sua reserva será confirmada automaticamente ou em até alguns minutos.
                        </p>
                    </div>

                    <div class="buttons is-centered mt-4">
                        <a href="<?= site_url('pedido/' . $order->id) ?>" class="button is-primary">
                            <span class="icon">
                                <i class="fas fa-sync"></i>
                            </span>
                            <span>Verificar Status</span>
                        </a>
                        <a href="<?= site_url('/') ?>" class="button is-light">
                            <span class="icon">
                                <i class="fas fa-home"></i>
                            </span>
                            <span>Voltar ao Início</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Copia código PIX
function copyPixCode() {
    const pixCode = document.getElementById('pixCode');
    pixCode.select();
    document.execCommand('copy');
    
    // Feedback visual
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="icon"><i class="fas fa-check"></i></span><span>Copiado!</span>';
    btn.classList.add('is-success');
    
    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.classList.remove('is-success');
    }, 2000);
}

// Countdown
const expiresAt = new Date('<?= $order->expires_at ?>').getTime();

function updateCountdown() {
    const now = new Date().getTime();
    const diff = expiresAt - now;
    
    if (diff <= 0) {
        document.getElementById('countdown').textContent = 'Expirado';
        window.location.reload();
        return;
    }
    
    const minutes = Math.floor(diff / 60000);
    const seconds = Math.floor((diff % 60000) / 1000);
    
    document.getElementById('countdown').textContent = 
        String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
}

setInterval(updateCountdown, 1000);
updateCountdown();

// Verifica status do pagamento periodicamente
function checkPaymentStatus() {
    fetch('<?= site_url('verificar-status/' . $order->id) ?>')
        .then(response => response.json())
        .then(data => {
            if (data.is_paid) {
                window.location.href = '<?= site_url('pedido/' . $order->id) ?>';
            } else if (data.is_expired) {
                window.location.reload();
            }
        })
        .catch(error => console.error('Erro:', error));
}

setInterval(checkPaymentStatus, 10000); // Verifica a cada 10 segundos
</script>
<?= $this->endSection() ?>
