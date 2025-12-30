<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<h1 class="title">Configurações de Pagamento</h1>

<?php if (session()->getFlashdata('success')): ?>
    <div class="notification is-success is-light">
        <button class="delete" onclick="this.parentElement.remove()"></button>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="notification is-danger is-light">
        <button class="delete" onclick="this.parentElement.remove()"></button>
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="tabs is-boxed">
    <ul>
        <li><a href="<?= site_url('admin/settings') ?>">Geral</a></li>
        <li class="is-active"><a href="<?= site_url('admin/settings/payment') ?>">Pagamento</a></li>
    </ul>
</div>

<form action="<?= site_url('admin/settings/payment') ?>" method="post">
    <?= csrf_field() ?>
    
    <div class="box">
        <h2 class="subtitle">
            <span class="icon has-text-success"><i class="fas fa-qrcode"></i></span>
            Configurações PIX
        </h2>
        
        <div class="notification is-info is-light">
            <p><strong>PIX é o método de pagamento principal.</strong> Configure sua chave PIX para receber pagamentos instantâneos.</p>
        </div>
        
        <div class="columns">
            <div class="column is-4">
                <div class="field">
                    <label class="label">Tipo de Chave PIX</label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="pix_key_type" id="pixKeyType">
                                <option value="cpf" <?= ($settings['pix_key_type'] ?? '') === 'cpf' ? 'selected' : '' ?>>CPF</option>
                                <option value="cnpj" <?= ($settings['pix_key_type'] ?? '') === 'cnpj' ? 'selected' : '' ?>>CNPJ</option>
                                <option value="email" <?= ($settings['pix_key_type'] ?? '') === 'email' ? 'selected' : '' ?>>Email</option>
                                <option value="phone" <?= ($settings['pix_key_type'] ?? '') === 'phone' ? 'selected' : '' ?>>Telefone</option>
                                <option value="random" <?= ($settings['pix_key_type'] ?? '') === 'random' ? 'selected' : '' ?>>Chave Aleatória</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-8">
                <div class="field">
                    <label class="label">Chave PIX</label>
                    <div class="control has-icons-left">
                        <input class="input" type="text" name="pix_key" id="pixKey" value="<?= esc($settings['pix_key'] ?? '') ?>" placeholder="Sua chave PIX">
                        <span class="icon is-small is-left">
                            <i class="fas fa-key"></i>
                        </span>
                    </div>
                    <p class="help" id="pixKeyHelp">Insira sua chave PIX cadastrada no banco.</p>
                </div>
            </div>
        </div>
        
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    <label class="label">Nome do Beneficiário</label>
                    <div class="control has-icons-left">
                        <input class="input" type="text" name="pix_name" value="<?= esc($settings['pix_name'] ?? '') ?>" placeholder="Nome que aparece no comprovante">
                        <span class="icon is-small is-left">
                            <i class="fas fa-user"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    <label class="label">Cidade do Beneficiário</label>
                    <div class="control has-icons-left">
                        <input class="input" type="text" name="pix_city" value="<?= esc($settings['pix_city'] ?? '') ?>" placeholder="Cidade (máx. 15 caracteres)">
                        <span class="icon is-small is-left">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="field">
            <label class="checkbox">
                <input type="checkbox" name="pix_enabled" value="1" <?= ($settings['pix_enabled'] ?? true) ? 'checked' : '' ?>>
                <strong>PIX habilitado</strong>
            </label>
        </div>
    </div>
    
    <div class="box">
        <h2 class="subtitle">
            <span class="icon has-text-warning"><i class="fas fa-credit-card"></i></span>
            Gateway de Pagamento (Opcional)
        </h2>
        
        <div class="notification is-warning is-light">
            <p>A integração com gateway permite confirmação automática de pagamentos. Configure apenas se tiver uma conta em algum gateway.</p>
        </div>
        
        <div class="field">
            <label class="label">Gateway</label>
            <div class="control">
                <div class="select">
                    <select name="payment_gateway" id="paymentGateway">
                        <option value="">Nenhum (Confirmação Manual)</option>
                        <option value="mercadopago" <?= ($settings['payment_gateway'] ?? '') === 'mercadopago' ? 'selected' : '' ?>>Mercado Pago</option>
                        <option value="pagseguro" <?= ($settings['payment_gateway'] ?? '') === 'pagseguro' ? 'selected' : '' ?>>PagSeguro</option>
                        <option value="stripe" <?= ($settings['payment_gateway'] ?? '') === 'stripe' ? 'selected' : '' ?>>Stripe</option>
                        <option value="asaas" <?= ($settings['payment_gateway'] ?? '') === 'asaas' ? 'selected' : '' ?>>Asaas</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div id="gatewayFields" style="<?= empty($settings['payment_gateway']) ? 'display: none;' : '' ?>">
            <div class="columns">
                <div class="column is-6">
                    <div class="field">
                        <label class="label">Access Token / API Key</label>
                        <div class="control has-icons-left">
                            <input class="input" type="password" name="gateway_api_key" value="<?= esc($settings['gateway_api_key'] ?? '') ?>" placeholder="Chave de API">
                            <span class="icon is-small is-left">
                                <i class="fas fa-key"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field">
                        <label class="label">Secret Key (se aplicável)</label>
                        <div class="control has-icons-left">
                            <input class="input" type="password" name="gateway_secret_key" value="<?= esc($settings['gateway_secret_key'] ?? '') ?>" placeholder="Chave secreta">
                            <span class="icon is-small is-left">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="field">
                <label class="label">URL de Webhook (Notificação)</label>
                <div class="control">
                    <input class="input" type="text" readonly value="<?= site_url('webhook/payment') ?>">
                </div>
                <p class="help">Configure esta URL no painel do seu gateway para receber notificações de pagamento.</p>
            </div>
            
            <div class="field">
                <label class="checkbox">
                    <input type="checkbox" name="gateway_sandbox" value="1" <?= ($settings['gateway_sandbox'] ?? false) ? 'checked' : '' ?>>
                    <strong>Modo Sandbox/Teste</strong>
                </label>
                <p class="help">Ative para testes. Desative em produção.</p>
            </div>
        </div>
    </div>
    
    <div class="box">
        <h2 class="subtitle">
            <span class="icon"><i class="fas fa-file-invoice-dollar"></i></span>
            Mensagens de Pagamento
        </h2>
        
        <div class="field">
            <label class="label">Instruções de Pagamento PIX</label>
            <div class="control">
                <textarea class="textarea" name="payment_instructions" rows="4" placeholder="Instruções exibidas na página de pagamento"><?= esc($settings['payment_instructions'] ?? "1. Abra o aplicativo do seu banco\n2. Acesse a área PIX\n3. Escaneie o QR Code ou copie o código\n4. Confirme o pagamento\n5. Aguarde a confirmação automática ou envie o comprovante") ?></textarea>
            </div>
        </div>
        
        <div class="field">
            <label class="label">Mensagem de Pagamento Confirmado</label>
            <div class="control">
                <textarea class="textarea" name="payment_success_message" rows="2" placeholder="Mensagem exibida após confirmação"><?= esc($settings['payment_success_message'] ?? 'Pagamento confirmado com sucesso! Seus números estão reservados. Boa sorte!') ?></textarea>
            </div>
        </div>
        
        <div class="field">
            <label class="label">WhatsApp para Suporte</label>
            <div class="control has-icons-left">
                <input class="input" type="tel" name="support_whatsapp" value="<?= esc($settings['support_whatsapp'] ?? '') ?>" placeholder="(00) 00000-0000">
                <span class="icon is-small is-left">
                    <i class="fab fa-whatsapp"></i>
                </span>
            </div>
            <p class="help">Número exibido para contato em caso de dúvidas sobre pagamento.</p>
        </div>
    </div>
    
    <div class="field is-grouped">
        <div class="control">
            <button type="submit" class="button is-primary is-medium">
                <span class="icon"><i class="fas fa-save"></i></span>
                <span>Salvar Configurações</span>
            </button>
        </div>
    </div>
</form>

<script>
document.getElementById('paymentGateway').addEventListener('change', function() {
    const gatewayFields = document.getElementById('gatewayFields');
    if (this.value) {
        gatewayFields.style.display = 'block';
    } else {
        gatewayFields.style.display = 'none';
    }
});

document.getElementById('pixKeyType').addEventListener('change', function() {
    const help = document.getElementById('pixKeyHelp');
    const input = document.getElementById('pixKey');
    
    switch(this.value) {
        case 'cpf':
            help.textContent = 'Formato: 000.000.000-00';
            input.placeholder = '000.000.000-00';
            break;
        case 'cnpj':
            help.textContent = 'Formato: 00.000.000/0000-00';
            input.placeholder = '00.000.000/0000-00';
            break;
        case 'email':
            help.textContent = 'Seu email cadastrado como chave PIX';
            input.placeholder = 'email@exemplo.com';
            break;
        case 'phone':
            help.textContent = 'Formato: +5500000000000 (com código do país)';
            input.placeholder = '+5500000000000';
            break;
        case 'random':
            help.textContent = 'Chave aleatória gerada pelo banco';
            input.placeholder = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';
            break;
    }
});
</script>
<?= $this->endSection() ?>
