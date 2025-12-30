<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Pagamento - Pedido #<?= $order->id ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
    .payment-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 5rem 0 4rem;
        margin-top: -2rem;
        position: relative;
        text-align: center;
        color: white;
    }
    
    .payment-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .payment-hero h1 {
        font-size: 2rem;
        font-weight: 800;
    }
    
    .order-badge {
        display: inline-block;
        background: rgba(255,255,255,0.2);
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        margin-top: 0.5rem;
        backdrop-filter: blur(10px);
    }
    
    .payment-main {
        margin-top: -3rem;
        position: relative;
        z-index: 10;
        padding-bottom: 3rem;
    }
    
    .payment-card {
        background: white;
        border-radius: 25px;
        padding: 2.5rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        text-align: center;
    }
    
    .timer-box {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 5px solid #ffc107;
    }
    
    .timer-box.urgent {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        border-left-color: #dc3545;
        animation: pulse-warning 1s infinite;
    }
    
    @keyframes pulse-warning {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.02); }
    }
    
    .timer-icon {
        font-size: 2rem;
        color: #856404;
        margin-bottom: 0.5rem;
    }
    
    .timer-box.urgent .timer-icon {
        color: #721c24;
    }
    
    .timer-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: #856404;
        font-family: 'Courier New', monospace;
    }
    
    .timer-box.urgent .timer-value {
        color: #721c24;
    }
    
    .timer-label {
        color: #856404;
        font-size: 0.875rem;
    }
    
    .timer-box.urgent .timer-label {
        color: #721c24;
    }
    
    .pix-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin: 2rem 0;
    }
    
    .qr-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 20px;
        padding: 1.5rem;
        text-align: center;
    }
    
    .qr-title {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #1a1a2e;
    }
    
    .qr-title i {
        color: #667eea;
    }
    
    .qr-image {
        background: white;
        padding: 1rem;
        border-radius: 15px;
        display: inline-block;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }
    
    .qr-image img {
        width: 200px;
        height: 200px;
        border-radius: 10px;
    }
    
    .qr-hint {
        color: #6c757d;
        font-size: 0.875rem;
        margin-top: 1rem;
    }
    
    .copy-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 20px;
        padding: 1.5rem;
        text-align: left;
    }
    
    .copy-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #1a1a2e;
    }
    
    .copy-title i {
        color: #28a745;
    }
    
    .pix-code-box {
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        font-family: 'Courier New', monospace;
        font-size: 0.75rem;
        word-break: break-all;
        max-height: 100px;
        overflow-y: auto;
    }
    
    .copy-btn {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .copy-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(40, 167, 69, 0.3);
    }
    
    .copy-btn.copied {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .amount-box {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border-radius: 20px;
        padding: 2rem;
        margin: 2rem 0;
        border-left: 5px solid #28a745;
    }
    
    .amount-label {
        font-size: 0.875rem;
        color: #155724;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
    }
    
    .amount-value {
        font-size: 3rem;
        font-weight: 800;
        color: #155724;
    }
    
    .order-details {
        background: #f8f9fa;
        border-radius: 20px;
        padding: 1.5rem;
        margin: 2rem 0;
        text-align: left;
    }
    
    .detail-title {
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .detail-title i {
        color: #667eea;
    }
    
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .detail-item {
        padding: 0.75rem;
        background: white;
        border-radius: 10px;
    }
    
    .detail-item .label {
        font-size: 0.75rem;
        color: #6c757d;
        text-transform: uppercase;
    }
    
    .detail-item .value {
        font-weight: 600;
        color: #1a1a2e;
    }
    
    .numbers-reserved {
        margin-top: 1rem;
    }
    
    .numbers-reserved .label {
        font-size: 0.75rem;
        color: #6c757d;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }
    
    .number-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .number-tag {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .instructions-box {
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 20px;
        padding: 1.5rem;
        margin: 2rem 0;
        text-align: left;
    }
    
    .instructions-title {
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .instructions-title i {
        color: #17a2b8;
    }
    
    .instruction-list {
        counter-reset: instruction;
        list-style: none;
        padding: 0;
    }
    
    .instruction-list li {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .instruction-list li:last-child {
        border-bottom: none;
    }
    
    .instruction-list li::before {
        counter-increment: instruction;
        content: counter(instruction);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.875rem;
        flex-shrink: 0;
    }
    
    .info-banner {
        background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
        border-radius: 15px;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 2rem 0;
        border-left: 4px solid #17a2b8;
    }
    
    .info-banner i {
        color: #0c5460;
        font-size: 1.5rem;
    }
    
    .info-banner p {
        color: #0c5460;
        margin: 0;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .action-btn {
        padding: 1rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .action-btn.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
    }
    
    .action-btn.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
        color: white;
    }
    
    .action-btn.secondary {
        background: white;
        color: #1a1a2e;
        border: 2px solid #e0e0e0;
    }
    
    .action-btn.secondary:hover {
        border-color: #667eea;
        color: #667eea;
    }
    
    @media (max-width: 768px) {
        .pix-section {
            grid-template-columns: 1fr;
        }
        
        .detail-grid {
            grid-template-columns: 1fr;
        }
        
        .amount-value {
            font-size: 2rem;
        }
    }
</style>

<!-- Hero Section -->
<section class="payment-hero">
    <div class="container">
        <i class="fas fa-clock fa-3x" style="opacity: 0.8; margin-bottom: 1rem;"></i>
        <h1>Aguardando Pagamento</h1>
        <div class="order-badge">
            <i class="fas fa-receipt"></i> Pedido #<?= str_pad($order->id, 6, '0', STR_PAD_LEFT) ?>
        </div>
    </div>
</section>

<!-- Payment Main -->
<section class="section payment-main">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-10 is-8-desktop">
                <div class="payment-card">
                    
                    <!-- Timer -->
                    <div class="timer-box" id="timerBox">
                        <div class="timer-icon">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div class="timer-value" id="countdown"><?= $order->getRemainingTime() ?></div>
                        <div class="timer-label">
                            Tempo restante para pagamento
                        </div>
                    </div>

                    <!-- PIX Section -->
                    <div class="pix-section">
                        <!-- QR Code -->
                        <div class="qr-card">
                            <div class="qr-title">
                                <i class="fas fa-qrcode"></i> QR Code PIX
                            </div>
                            <div class="qr-image">
                                <img src="<?= esc($qrCodeUrl) ?>" alt="QR Code PIX">
                            </div>
                            <p class="qr-hint">
                                <i class="fas fa-mobile-alt"></i> Escaneie com o app do seu banco
                            </p>
                        </div>

                        <!-- Copy & Paste -->
                        <div class="copy-card">
                            <div class="copy-title">
                                <i class="fas fa-copy"></i> PIX Copia e Cola
                            </div>
                            <div class="pix-code-box" id="pixCode"><?= esc($pixCode) ?></div>
                            <button class="copy-btn" onclick="copyPixCode()" id="copyBtn">
                                <i class="fas fa-copy"></i>
                                <span>Copiar Código PIX</span>
                            </button>
                            <p class="qr-hint" style="margin-top: 1rem;">
                                <i class="fas fa-info-circle"></i> Cole no app do seu banco
                            </p>
                        </div>
                    </div>

                    <!-- Amount -->
                    <div class="amount-box">
                        <div class="amount-label">
                            <i class="fas fa-money-bill-wave"></i> Valor a Pagar
                        </div>
                        <div class="amount-value"><?= $order->getFormattedAmount() ?></div>
                    </div>

                    <!-- Order Details -->
                    <div class="order-details">
                        <div class="detail-title">
                            <i class="fas fa-shopping-bag"></i> Detalhes do Pedido
                        </div>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <div class="label">Rifa</div>
                                <div class="value"><?= esc($order->raffle_name ?? $raffle->name ?? 'N/A') ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="label">Quantidade</div>
                                <div class="value"><?= $order->total_numbers ?> número(s)</div>
                            </div>
                            <div class="detail-item">
                                <div class="label">Comprador</div>
                                <div class="value"><?= esc($order->customer_name) ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="label">E-mail</div>
                                <div class="value"><?= esc($order->customer_email) ?></div>
                            </div>
                        </div>
                        
                        <div class="numbers-reserved">
                            <div class="label">Números Reservados</div>
                            <div class="number-tags">
                                <?php foreach ($numbers as $number): ?>
                                    <span class="number-tag">
                                        <?= $number->getFormattedNumber($raffle->getNumberDigits()) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="instructions-box">
                        <div class="instructions-title">
                            <i class="fas fa-list-ol"></i> Como Pagar
                        </div>
                        <ol class="instruction-list">
                            <li>Abra o aplicativo do seu banco</li>
                            <li>Escolha a opção de pagamento via PIX</li>
                            <li>Escaneie o QR Code ou cole o código copiado</li>
                            <li>Confirme os dados e efetue o pagamento</li>
                            <li>Pronto! A confirmação é automática e instantânea</li>
                        </ol>
                    </div>

                    <!-- Info Banner -->
                    <div class="info-banner">
                        <i class="fas fa-info-circle"></i>
                        <p>Após o pagamento, sua reserva será confirmada automaticamente. Você receberá um e-mail de confirmação.</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <a href="<?= site_url('pedido/' . $order->id) ?>" class="action-btn primary">
                            <i class="fas fa-sync"></i> Verificar Status
                        </a>
                        <a href="<?= site_url('/') ?>" class="action-btn secondary">
                            <i class="fas fa-home"></i> Voltar ao Início
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
    const pixCode = document.getElementById('pixCode').textContent;
    navigator.clipboard.writeText(pixCode).then(() => {
        const btn = document.getElementById('copyBtn');
        btn.innerHTML = '<i class="fas fa-check"></i> <span>Copiado!</span>';
        btn.classList.add('copied');
        
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-copy"></i> <span>Copiar Código PIX</span>';
            btn.classList.remove('copied');
        }, 3000);
    });
}

// Countdown
const expiresAt = new Date('<?= $order->expires_at ?>').getTime();

function updateCountdown() {
    const now = new Date().getTime();
    const diff = expiresAt - now;
    const timerBox = document.getElementById('timerBox');
    
    if (diff <= 0) {
        document.getElementById('countdown').textContent = 'Expirado';
        timerBox.classList.add('urgent');
        window.location.reload();
        return;
    }
    
    const minutes = Math.floor(diff / 60000);
    const seconds = Math.floor((diff % 60000) / 1000);
    
    // Adiciona classe urgente quando restar menos de 5 minutos
    if (minutes < 5) {
        timerBox.classList.add('urgent');
    }
    
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
