<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Comprar - <?= esc($raffle->name) ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
.number-item.selected {
    background-color: #3273dc !important;
    color: white !important;
    border-color: #2160c4;
    transform: scale(1.05);
}
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="container">
        <nav class="breadcrumb" aria-label="breadcrumbs">
            <ul>
                <li><a href="<?= site_url('/') ?>">Início</a></li>
                <li><a href="<?= site_url('rifa/' . $raffle->slug) ?>"><?= esc($raffle->name) ?></a></li>
                <li class="is-active"><a href="#" aria-current="page">Comprar</a></li>
            </ul>
        </nav>

        <form action="<?= site_url('processar-compra') ?>" method="post" id="checkoutForm">
            <?= csrf_field() ?>
            <input type="hidden" name="raffle_id" value="<?= $raffle->id ?>">

            <div class="columns">
                <!-- Seleção de Números -->
                <div class="column is-8">
                    <div class="box">
                        <h2 class="title is-4">
                            <i class="fas fa-hand-pointer"></i> Selecione seus Números
                        </h2>
                        <p class="subtitle is-6 has-text-grey">
                            <?= esc($raffle->name) ?> - <?= $raffle->getFormattedPrice() ?> por número
                        </p>

                        <!-- Botões de seleção rápida -->
                        <div class="buttons mb-4">
                            <button type="button" class="button is-small is-info is-outlined" onclick="selectRandom(5)">
                                <i class="fas fa-random mr-1"></i> 5 Aleatórios
                            </button>
                            <button type="button" class="button is-small is-info is-outlined" onclick="selectRandom(10)">
                                <i class="fas fa-random mr-1"></i> 10 Aleatórios
                            </button>
                            <button type="button" class="button is-small is-info is-outlined" onclick="selectRandom(20)">
                                <i class="fas fa-random mr-1"></i> 20 Aleatórios
                            </button>
                            <button type="button" class="button is-small is-danger is-outlined" onclick="clearSelection()">
                                <i class="fas fa-times mr-1"></i> Limpar
                            </button>
                        </div>

                        <!-- Busca de número -->
                        <div class="field has-addons mb-4">
                            <div class="control is-expanded">
                                <input class="input" type="number" id="searchNumber" placeholder="Buscar número...">
                            </div>
                            <div class="control">
                                <button type="button" class="button is-info" onclick="searchNumber()">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Legenda -->
                        <div class="mb-4">
                            <span class="tag is-success">
                                <span class="icon"><i class="fas fa-square"></i></span>
                                <span>Disponível</span>
                            </span>
                            <span class="tag is-warning ml-2">
                                <span class="icon"><i class="fas fa-square"></i></span>
                                <span>Reservado</span>
                            </span>
                            <span class="tag is-danger ml-2">
                                <span class="icon"><i class="fas fa-square"></i></span>
                                <span>Vendido</span>
                            </span>
                            <span class="tag is-link ml-2">
                                <span class="icon"><i class="fas fa-square"></i></span>
                                <span>Selecionado</span>
                            </span>
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
                        <div class="box">
                            <h3 class="title is-5">
                                <i class="fas fa-shopping-cart"></i> Resumo do Pedido
                            </h3>

                            <div class="mb-4">
                                <figure class="image is-16by9 mb-3">
                                    <img src="<?= esc($raffle->getImageUrl()) ?>" alt="<?= esc($raffle->name) ?>" style="border-radius: 8px; object-fit: cover;">
                                </figure>
                                <p class="has-text-weight-bold"><?= esc($raffle->name) ?></p>
                            </div>

                            <div class="content">
                                <div class="level is-mobile">
                                    <div class="level-left">Números selecionados:</div>
                                    <div class="level-right"><strong id="selectedCount">0</strong></div>
                                </div>
                                <div class="level is-mobile">
                                    <div class="level-left">Preço unitário:</div>
                                    <div class="level-right"><?= $raffle->getFormattedPrice() ?></div>
                                </div>
                                <hr>
                                <div class="level is-mobile">
                                    <div class="level-left"><strong>Total:</strong></div>
                                    <div class="level-right"><strong class="is-size-4 has-text-success" id="totalAmount">R$ 0,00</strong></div>
                                </div>
                            </div>

                            <div id="selectedNumbersList" class="mb-4" style="max-height: 150px; overflow-y: auto;">
                                <p class="has-text-grey has-text-centered">Nenhum número selecionado</p>
                            </div>
                        </div>

                        <!-- Dados do Comprador -->
                        <div class="box">
                            <h3 class="title is-5">
                                <i class="fas fa-user"></i> Seus Dados
                            </h3>

                            <?php if (session()->getFlashdata('errors')): ?>
                                <div class="notification is-danger is-light">
                                    <ul>
                                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                            <li><?= esc($error) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <div class="field">
                                <label class="label">Nome Completo *</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="text" name="customer_name" 
                                           value="<?= old('customer_name', session()->get('user_name') ?? '') ?>" 
                                           placeholder="Seu nome completo" required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">E-mail *</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="email" name="customer_email" 
                                           value="<?= old('customer_email', session()->get('user_email') ?? '') ?>" 
                                           placeholder="seu@email.com" required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">WhatsApp *</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="tel" name="customer_phone" 
                                           value="<?= old('customer_phone') ?>" 
                                           placeholder="(00) 00000-0000" required>
                                    <span class="icon is-small is-left">
                                        <i class="fab fa-whatsapp"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">CPF (opcional)</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="text" name="customer_cpf" 
                                           value="<?= old('customer_cpf') ?>" 
                                           placeholder="000.000.000-00">
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-id-card"></i>
                                    </span>
                                </div>
                            </div>

                            <button type="submit" class="button is-success is-fullwidth is-medium" id="submitBtn" disabled>
                                <span class="icon">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <span>Ir para Pagamento</span>
                            </button>

                            <p class="has-text-grey has-text-centered mt-2 is-size-7">
                                <i class="fas fa-shield-alt"></i> Seus dados estão seguros
                            </p>
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
let selectedNumbers = [];

function toggleNumber(element) {
    const status = element.dataset.status;
    const number = element.dataset.number;
    
    if (status !== 'available') {
        return;
    }
    
    if (element.classList.contains('selected')) {
        // Remove seleção
        element.classList.remove('selected');
        selectedNumbers = selectedNumbers.filter(n => n !== number);
    } else {
        // Verifica limite
        if (selectedNumbers.length >= maxNumbers) {
            alert(`Você pode selecionar no máximo ${maxNumbers} números por compra.`);
            return;
        }
        
        // Adiciona seleção
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
        listContainer.innerHTML = '<p class="has-text-grey has-text-centered">Nenhum número selecionado</p>';
    } else {
        let html = '<div class="tags">';
        selectedNumbers.sort((a, b) => parseInt(a) - parseInt(b)).forEach(num => {
            html += `<span class="tag is-link">${num.toString().padStart(<?= $raffle->getNumberDigits() ?>, '0')}</span>`;
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
    
    // Limita pela quantidade máxima permitida
    const remaining = maxNumbers - selectedNumbers.length;
    const toSelect = Math.min(count, remaining, availableArray.length);
    
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
}

function clearSelection() {
    document.querySelectorAll('.number-item.selected').forEach(el => {
        el.classList.remove('selected');
    });
    selectedNumbers = [];
    updateSummary();
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
        alert('Número não encontrado.');
    }
}

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
</script>
<?= $this->endSection() ?>
