<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Comprar - <?= esc($raffle->name) ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    <?php
    // Fallback images do Unsplash
    $fallbackImages = [
        'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&q=80',
        'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&q=80',
        'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=800&q=80',
    ];
    $raffleImage = $raffle->image ? base_url('uploads/raffles/' . $raffle->image) : $fallbackImages[($raffle->id ?? 0) % count($fallbackImages)];
    ?>
    
    .checkout-hero {
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.95), rgba(32, 201, 151, 0.95)),
                    url('<?= $raffleImage ?>') center/cover;
        padding: 6rem 0 4rem;
        margin-top: -2rem;
        position: relative;
    }
    
    .checkout-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .checkout-title {
        color: white;
        font-size: 2.5rem;
        font-weight: 800;
        text-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }
    
    .checkout-subtitle {
        color: rgba(255,255,255,0.9);
        font-size: 1.25rem;
    }
    
    .breadcrumb-checkout {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50px;
        padding: 0.5rem 1.5rem;
        display: inline-block;
        backdrop-filter: blur(10px);
        margin-bottom: 1.5rem;
    }
    
    .breadcrumb-checkout a {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    
    .breadcrumb-checkout a:hover {
        color: #FFD700 !important;
    }
    
    .breadcrumb-checkout li.is-active a {
        color: white !important;
        font-weight: 600;
    }
    
    .breadcrumb-checkout li + li::before {
        color: rgba(255, 255, 255, 0.5) !important;
    }
    
    .checkout-main {
        margin-top: -3rem;
        position: relative;
        z-index: 10;
    }
    
    .selection-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }
    
    .card-header-custom {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .card-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }
    
    .card-icon.green {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }
    
    .card-icon.blue {
        background: linear-gradient(135deg, #3273dc 0%, #209cee 100%);
    }
    
    .quick-select-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }
    
    .quick-btn {
        padding: 0.75rem 1.25rem;
        border: 2px solid #e0e0e0;
        background: white;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .quick-btn:hover {
        border-color: #667eea;
        color: #667eea;
        transform: translateY(-2px);
    }
    
    .quick-btn.random {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
    }
    
    .quick-btn.random:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }
    
    .quick-btn.clear {
        background: #f8f9fa;
        color: #dc3545;
        border-color: #dc3545;
    }
    
    .quick-btn.clear:hover {
        background: #dc3545;
        color: white;
    }
    
    .search-box {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }
    
    .search-input {
        flex: 1;
        padding: 1rem 1.5rem;
        border: 2px solid #e0e0e0;
        border-radius: 50px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .search-btn {
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .search-btn:hover {
        transform: scale(1.05);
    }
    
    .legend-modern {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 15px;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }
    
    .legend-dot {
        width: 16px;
        height: 16px;
        border-radius: 4px;
    }
    
    .legend-dot.available { background: linear-gradient(135deg, #d4edda, #c3e6cb); border: 2px solid #28a745; }
    .legend-dot.reserved { background: linear-gradient(135deg, #fff3cd, #ffeeba); border: 2px solid #ffc107; }
    .legend-dot.sold { background: linear-gradient(135deg, #f8d7da, #f5c6cb); border: 2px solid #dc3545; }
    .legend-dot.selected { background: linear-gradient(135deg, #667eea, #764ba2); border: 2px solid #5a67d8; }
    
    .number-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(55px, 1fr));
        gap: 8px;
        max-height: 400px;
        overflow-y: auto;
        padding: 0.5rem;
    }
    
    .number-item {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 2px solid transparent;
    }
    
    .number-item.available {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
        border-color: #28a745;
    }
    
    .number-item.available:hover {
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        z-index: 5;
    }
    
    .number-item.reserved {
        background: linear-gradient(135deg, #fff3cd, #ffeeba);
        color: #856404;
        border-color: #ffc107;
        cursor: not-allowed;
    }
    
    .number-item.sold {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        color: #721c24;
        border-color: #dc3545;
        cursor: not-allowed;
    }
    
    .number-item.selected {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        border-color: #5a67d8 !important;
        transform: scale(1.05);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.15); }
    }
    
    .sticky-cart {
        position: sticky;
        top: 100px;
    }
    
    .cart-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
    }
    
    .cart-product {
        display: flex;
        gap: 1rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
        margin-bottom: 1rem;
    }
    
    .cart-product img {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
    }
    
    .cart-product-info h4 {
        font-weight: 700;
        color: #1a1a2e;
        font-size: 1rem;
        margin-bottom: 0.25rem;
    }
    
    .cart-product-info .price {
        color: #28a745;
        font-weight: 600;
    }
    
    .cart-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
    }
    
    .cart-row.total {
        border-top: 2px solid #f0f0f0;
        margin-top: 0.5rem;
        padding-top: 1rem;
    }
    
    .cart-row.total .value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #28a745;
    }
    
    .selected-numbers-box {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1rem;
        max-height: 120px;
        overflow-y: auto;
        margin-bottom: 1rem;
    }
    
    .selected-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .number-tag {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .form-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }
    
    .form-field {
        margin-bottom: 1rem;
    }
    
    .form-field label {
        display: block;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0.5rem;
    }
    
    .form-input {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .input-icon-wrapper {
        position: relative;
    }
    
    .input-icon-wrapper i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    
    .submit-btn {
        width: 100%;
        padding: 1.25rem;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: none;
        border-radius: 15px;
        font-size: 1.125rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }
    
    .submit-btn:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
    }
    
    .submit-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
    }
    
    .security-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1rem;
        color: #6c757d;
        font-size: 0.875rem;
    }
    
    .security-badge i {
        color: #28a745;
    }
    
    .error-notification {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        border-left: 4px solid #dc3545;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .error-notification ul {
        margin: 0;
        padding-left: 1.25rem;
    }
    
    @media (max-width: 768px) {
        .checkout-hero {
            padding: 5rem 0 3rem;
        }
        
        .checkout-title {
            font-size: 1.75rem;
        }
        
        .quick-select-bar {
            justify-content: center;
        }
        
        .number-grid {
            grid-template-columns: repeat(auto-fill, minmax(45px, 1fr));
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="checkout-hero">
    <div class="container">
        <nav class="breadcrumb breadcrumb-checkout" aria-label="breadcrumbs">
            <ul>
                <li><a href="<?= site_url('/') ?>"><i class="fas fa-home"></i> Início</a></li>
                <li><a href="<?= site_url('rifa/' . $raffle->slug) ?>"><?= esc($raffle->name) ?></a></li>
                <li class="is-active"><a href="#" aria-current="page">Comprar</a></li>
            </ul>
        </nav>
        
        <h1 class="checkout-title">
            <i class="fas fa-shopping-cart"></i> Escolha seus Números da Sorte
        </h1>
        <p class="checkout-subtitle">
            <?= esc($raffle->name) ?> • <?= $raffle->getFormattedPrice() ?> por número
        </p>
    </div>
</section>

<!-- Main Checkout -->
<section class="section checkout-main">
    <div class="container">
        <form action="<?= site_url('processar-compra') ?>" method="post" id="checkoutForm">
            <?= csrf_field() ?>
            <input type="hidden" name="raffle_id" value="<?= $raffle->id ?>">

            <div class="columns is-variable is-6">
                <!-- Seleção de Números -->
                <div class="column is-8">
                    <div class="selection-card">
                        <div class="card-header-custom">
                            <div class="card-icon">
                                <i class="fas fa-hand-pointer"></i>
                            </div>
                            <div>
                                <h2 class="title is-4 mb-0">Selecione seus Números</h2>
                                <p class="has-text-grey">Clique nos números disponíveis para selecioná-los</p>
                            </div>
                        </div>

                        <!-- Botões de seleção rápida -->
                        <div class="quick-select-bar">
                            <button type="button" class="quick-btn random" onclick="selectRandom(5)">
                                <i class="fas fa-dice"></i> 5 Aleatórios
                            </button>
                            <button type="button" class="quick-btn random" onclick="selectRandom(10)">
                                <i class="fas fa-dice"></i> 10 Aleatórios
                            </button>
                            <button type="button" class="quick-btn random" onclick="selectRandom(20)">
                                <i class="fas fa-dice"></i> 20 Aleatórios
                            </button>
                            <button type="button" class="quick-btn clear" onclick="clearSelection()">
                                <i class="fas fa-times"></i> Limpar
                            </button>
                        </div>

                        <!-- Busca de número -->
                        <div class="search-box">
                            <input class="search-input" type="number" id="searchNumber" placeholder="🔍 Buscar número específico...">
                            <button type="button" class="search-btn" onclick="searchNumber()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>

                        <!-- Legenda -->
                        <div class="legend-modern">
                            <div class="legend-item">
                                <div class="legend-dot available"></div>
                                <span>Disponível</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-dot reserved"></div>
                                <span>Reservado</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-dot sold"></div>
                                <span>Vendido</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-dot selected"></div>
                                <span>Selecionado</span>
                            </div>
                        </div>

                        <!-- Grid de números -->
                        <div class="number-grid" id="numberGrid">
                            <?php foreach ($numbers as $number): ?>
                                <div class="number-item <?= $number->status ?>" 
                                     data-number="<?= $number->number ?>"
                                     data-id="<?= $number->id ?>"
                                     data-status="<?= $number->status ?>"
                                     onclick="toggleNumber(this)">
                                    <?= $number->getFormattedNumber($raffle->getNumberDigits()) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Carrinho / Resumo -->
                <div class="column is-4">
                    <div class="sticky-cart">
                        <!-- Resumo do Pedido -->
                        <div class="cart-card">
                            <div class="card-header-custom">
                                <div class="card-icon green">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div>
                                    <h3 class="title is-5 mb-0">Resumo do Pedido</h3>
                                </div>
                            </div>

                            <div class="cart-product">
                                <?php
                                $fallbackImages = [
                                    'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=200&q=80',
                                    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&q=80',
                                ];
                                $thumbImage = $raffle->image ? base_url('uploads/raffles/' . $raffle->image) : $fallbackImages[($raffle->id ?? 0) % count($fallbackImages)];
                                ?>
                                <img src="<?= $thumbImage ?>" alt="<?= esc($raffle->name) ?>">
                                <div class="cart-product-info">
                                    <h4><?= esc($raffle->name) ?></h4>
                                    <span class="price"><?= $raffle->getFormattedPrice() ?> / número</span>
                                </div>
                            </div>

                            <div class="cart-row">
                                <span>Números selecionados:</span>
                                <strong id="selectedCount">0</strong>
                            </div>
                            <div class="cart-row">
                                <span>Preço unitário:</span>
                                <span><?= $raffle->getFormattedPrice() ?></span>
                            </div>
                            <div class="cart-row total">
                                <span>Total:</span>
                                <span class="value" id="totalAmount">R$ 0,00</span>
                            </div>

                            <div class="selected-numbers-box" id="selectedNumbersList">
                                <p class="has-text-grey has-text-centered">
                                    <i class="fas fa-mouse-pointer"></i> Selecione números acima
                                </p>
                            </div>
                        </div>

                        <!-- Dados do Comprador -->
                        <div class="form-card">
                            <div class="card-header-custom">
                                <div class="card-icon blue">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <h3 class="title is-5 mb-0">Seus Dados</h3>
                                </div>
                            </div>

                            <?php if (session()->getFlashdata('errors')): ?>
                                <div class="error-notification">
                                    <ul>
                                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                            <li><?= esc($error) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <div class="form-field">
                                <label>Nome Completo *</label>
                                <div class="input-icon-wrapper">
                                    <i class="fas fa-user"></i>
                                    <input class="form-input" type="text" name="customer_name" 
                                           value="<?= old('customer_name', session()->get('user_name') ?? '') ?>" 
                                           placeholder="Seu nome completo" required>
                                </div>
                            </div>

                            <div class="form-field">
                                <label>E-mail *</label>
                                <div class="input-icon-wrapper">
                                    <i class="fas fa-envelope"></i>
                                    <input class="form-input" type="email" name="customer_email" 
                                           value="<?= old('customer_email', session()->get('user_email') ?? '') ?>" 
                                           placeholder="seu@email.com" required>
                                </div>
                            </div>

                            <div class="form-field">
                                <label>WhatsApp *</label>
                                <div class="input-icon-wrapper">
                                    <i class="fab fa-whatsapp"></i>
                                    <input class="form-input" type="tel" name="customer_phone" 
                                           value="<?= old('customer_phone') ?>" 
                                           placeholder="(00) 00000-0000" required>
                                </div>
                            </div>

                            <div class="form-field">
                                <label>CPF (opcional)</label>
                                <div class="input-icon-wrapper">
                                    <i class="fas fa-id-card"></i>
                                    <input class="form-input" type="text" name="customer_cpf" 
                                           value="<?= old('customer_cpf') ?>" 
                                           placeholder="000.000.000-00">
                                </div>
                            </div>

                            <button type="submit" class="submit-btn" id="submitBtn" disabled>
                                <i class="fas fa-lock"></i>
                                Ir para Pagamento
                            </button>

                            <div class="security-badge">
                                <i class="fas fa-shield-alt"></i>
                                <span>Seus dados estão seguros e protegidos</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const price = <?= $raffle->price ?>;
const minNumbers = <?= $raffle->min_per_purchase ?>;
const maxNumbers = <?= $raffle->max_per_purchase ?>;
const numberDigits = <?= $raffle->getNumberDigits() ?>;
let selectedNumbers = [];

function toggleNumber(element) {
    const status = element.dataset.status;
    const number = element.dataset.number;
    
    if (status !== 'available') {
        // Mostrar feedback visual para números não disponíveis
        element.style.animation = 'shake 0.5s ease-in-out';
        setTimeout(() => element.style.animation = '', 500);
        return;
    }
    
    if (element.classList.contains('selected')) {
        // Remove seleção
        element.classList.remove('selected');
        selectedNumbers = selectedNumbers.filter(n => n !== number);
    } else {
        // Verifica limite
        if (selectedNumbers.length >= maxNumbers) {
            showNotification(`Máximo de ${maxNumbers} números por compra!`, 'warning');
            return;
        }
        
        // Adiciona seleção com animação
        element.classList.add('selected');
        selectedNumbers.push(number);
    }
    
    updateSummary();
}

function updateSummary() {
    const count = selectedNumbers.length;
    const total = count * price;
    
    document.getElementById('selectedCount').textContent = count;
    document.getElementById('totalAmount').textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
    
    // Atualiza lista de números selecionados
    const listContainer = document.getElementById('selectedNumbersList');
    if (count === 0) {
        listContainer.innerHTML = '<p class="has-text-grey has-text-centered"><i class="fas fa-mouse-pointer"></i> Selecione números acima</p>';
    } else {
        let html = '<div class="selected-tags">';
        selectedNumbers.sort((a, b) => parseInt(a) - parseInt(b)).forEach(num => {
            html += `<span class="number-tag">${num.toString().padStart(numberDigits, '0')}</span>`;
        });
        html += '</div>';
        listContainer.innerHTML = html;
    }
    
    // Atualiza hidden inputs
    document.querySelectorAll('input[name="numbers[]"]').forEach(el => el.remove());
    selectedNumbers.forEach(num => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'numbers[]';
        input.value = num;
        document.getElementById('checkoutForm').appendChild(input);
    });
    
    // Habilita/desabilita botão
    const submitBtn = document.getElementById('submitBtn');
    if (count >= minNumbers && count <= maxNumbers) {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;
    }
}

function selectRandom(count) {
    const availableItems = document.querySelectorAll('.number-item.available:not(.selected)');
    const availableArray = Array.from(availableItems);
    
    if (availableArray.length === 0) {
        showNotification('Não há números disponíveis!', 'danger');
        return;
    }
    
    // Limita pela quantidade máxima permitida
    const remaining = maxNumbers - selectedNumbers.length;
    const toSelect = Math.min(count, remaining, availableArray.length);
    
    if (remaining <= 0) {
        showNotification(`Você já selecionou o máximo de ${maxNumbers} números!`, 'warning');
        return;
    }
    
    // Embaralha e seleciona
    for (let i = availableArray.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [availableArray[i], availableArray[j]] = [availableArray[j], availableArray[i]];
    }
    
    for (let i = 0; i < toSelect; i++) {
        availableArray[i].classList.add('selected');
        selectedNumbers.push(availableArray[i].dataset.number);
    }
    
    updateSummary();
    showNotification(`${toSelect} números selecionados!`, 'success');
}

function clearSelection() {
    document.querySelectorAll('.number-item.selected').forEach(el => {
        el.classList.remove('selected');
    });
    selectedNumbers = [];
    updateSummary();
    showNotification('Seleção limpa!', 'info');
}

function searchNumber() {
    const searchValue = document.getElementById('searchNumber').value;
    if (!searchValue) return;
    
    const element = document.querySelector(`.number-item[data-number="${searchValue}"]`);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'center' });
        element.style.animation = 'pulse 0.5s ease-in-out 3';
        setTimeout(() => element.style.animation = '', 1500);
    } else {
        showNotification('Número não encontrado!', 'warning');
    }
}

function showNotification(message, type = 'info') {
    // Remove notificações existentes
    document.querySelectorAll('.floating-notification').forEach(el => el.remove());
    
    const colors = {
        success: '#28a745',
        warning: '#ffc107',
        danger: '#dc3545',
        info: '#667eea'
    };
    
    const icons = {
        success: 'check-circle',
        warning: 'exclamation-triangle',
        danger: 'times-circle',
        info: 'info-circle'
    };
    
    const notification = document.createElement('div');
    notification.className = 'floating-notification';
    notification.style.cssText = `
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: ${colors[type]};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideIn 0.3s ease;
    `;
    notification.innerHTML = `<i class="fas fa-${icons[type]}"></i> ${message}`;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Adiciona animações CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);

// Máscara de telefone
document.querySelector('input[name="customer_phone"]').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 11) value = value.slice(0, 11);
    if (value.length > 6) {
        value = `(${value.slice(0,2)}) ${value.slice(2,7)}-${value.slice(7)}`;
    } else if (value.length > 2) {
        value = `(${value.slice(0,2)}) ${value.slice(2)}`;
    }
    e.target.value = value;
});

// Máscara de CPF
document.querySelector('input[name="customer_cpf"]').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 11) value = value.slice(0, 11);
    if (value.length > 9) {
        value = `${value.slice(0,3)}.${value.slice(3,6)}.${value.slice(6,9)}-${value.slice(9)}`;
    } else if (value.length > 6) {
        value = `${value.slice(0,3)}.${value.slice(3,6)}.${value.slice(6)}`;
    } else if (value.length > 3) {
        value = `${value.slice(0,3)}.${value.slice(3)}`;
    }
    e.target.value = value;
});

// Enter na busca
document.getElementById('searchNumber').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        searchNumber();
    }
});
</script>
<?= $this->endSection() ?>
