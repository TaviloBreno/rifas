<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<h1 class="title">Configurações</h1>

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
        <li class="is-active"><a href="<?= site_url('admin/settings') ?>">Geral</a></li>
        <li><a href="<?= site_url('admin/settings/payment') ?>">Pagamento</a></li>
    </ul>
</div>

<form action="<?= site_url('admin/settings') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    
    <div class="box">
        <h2 class="subtitle">
            <span class="icon"><i class="fas fa-globe"></i></span>
            Informações do Site
        </h2>
        
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    <label class="label">Nome do Site</label>
                    <div class="control">
                        <input class="input" type="text" name="site_name" value="<?= esc($settings['site_name'] ?? 'Rifas Online') ?>" placeholder="Nome do seu site">
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    <label class="label">URL do Site</label>
                    <div class="control has-icons-left">
                        <input class="input" type="url" name="site_url" value="<?= esc($settings['site_url'] ?? base_url()) ?>" placeholder="https://seusite.com">
                        <span class="icon is-small is-left">
                            <i class="fas fa-link"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="field">
            <label class="label">Descrição do Site</label>
            <div class="control">
                <textarea class="textarea" name="site_description" rows="3" placeholder="Descrição breve do site para SEO"><?= esc($settings['site_description'] ?? '') ?></textarea>
            </div>
        </div>
        
        <div class="field">
            <label class="label">Palavras-chave (SEO)</label>
            <div class="control">
                <input class="input" type="text" name="site_keywords" value="<?= esc($settings['site_keywords'] ?? '') ?>" placeholder="rifas, sorteios, prêmios, concursos">
            </div>
            <p class="help">Separe as palavras por vírgula.</p>
        </div>
    </div>
    
    <div class="box">
        <h2 class="subtitle">
            <span class="icon"><i class="fas fa-address-card"></i></span>
            Informações de Contato
        </h2>
        
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    <label class="label">Email de Contato</label>
                    <div class="control has-icons-left">
                        <input class="input" type="email" name="contact_email" value="<?= esc($settings['contact_email'] ?? '') ?>" placeholder="contato@seusite.com">
                        <span class="icon is-small is-left">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    <label class="label">Telefone / WhatsApp</label>
                    <div class="control has-icons-left">
                        <input class="input" type="tel" name="contact_phone" value="<?= esc($settings['contact_phone'] ?? '') ?>" placeholder="(00) 00000-0000">
                        <span class="icon is-small is-left">
                            <i class="fas fa-phone"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="field">
            <label class="label">Endereço</label>
            <div class="control">
                <textarea class="textarea" name="contact_address" rows="2" placeholder="Endereço completo"><?= esc($settings['contact_address'] ?? '') ?></textarea>
            </div>
        </div>
    </div>
    
    <div class="box">
        <h2 class="subtitle">
            <span class="icon"><i class="fas fa-share-alt"></i></span>
            Redes Sociais
        </h2>
        
        <div class="columns is-multiline">
            <div class="column is-6">
                <div class="field">
                    <label class="label">Facebook</label>
                    <div class="control has-icons-left">
                        <input class="input" type="url" name="social_facebook" value="<?= esc($settings['social_facebook'] ?? '') ?>" placeholder="https://facebook.com/suapagina">
                        <span class="icon is-small is-left">
                            <i class="fab fa-facebook"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    <label class="label">Instagram</label>
                    <div class="control has-icons-left">
                        <input class="input" type="url" name="social_instagram" value="<?= esc($settings['social_instagram'] ?? '') ?>" placeholder="https://instagram.com/seuperfil">
                        <span class="icon is-small is-left">
                            <i class="fab fa-instagram"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    <label class="label">YouTube</label>
                    <div class="control has-icons-left">
                        <input class="input" type="url" name="social_youtube" value="<?= esc($settings['social_youtube'] ?? '') ?>" placeholder="https://youtube.com/seucanal">
                        <span class="icon is-small is-left">
                            <i class="fab fa-youtube"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    <label class="label">TikTok</label>
                    <div class="control has-icons-left">
                        <input class="input" type="url" name="social_tiktok" value="<?= esc($settings['social_tiktok'] ?? '') ?>" placeholder="https://tiktok.com/@seuperfil">
                        <span class="icon is-small is-left">
                            <i class="fab fa-tiktok"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="box">
        <h2 class="subtitle">
            <span class="icon"><i class="fas fa-cog"></i></span>
            Configurações de Rifas
        </h2>
        
        <div class="columns">
            <div class="column is-4">
                <div class="field">
                    <label class="label">Tempo de Expiração do Pedido</label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="order_expiration_minutes">
                                <option value="15" <?= ($settings['order_expiration_minutes'] ?? 30) == 15 ? 'selected' : '' ?>>15 minutos</option>
                                <option value="30" <?= ($settings['order_expiration_minutes'] ?? 30) == 30 ? 'selected' : '' ?>>30 minutos</option>
                                <option value="60" <?= ($settings['order_expiration_minutes'] ?? 30) == 60 ? 'selected' : '' ?>>1 hora</option>
                                <option value="120" <?= ($settings['order_expiration_minutes'] ?? 30) == 120 ? 'selected' : '' ?>>2 horas</option>
                                <option value="1440" <?= ($settings['order_expiration_minutes'] ?? 30) == 1440 ? 'selected' : '' ?>>24 horas</option>
                            </select>
                        </div>
                    </div>
                    <p class="help">Tempo para pagamento antes do pedido expirar.</p>
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    <label class="label">Mínimo de Números por Compra</label>
                    <div class="control">
                        <input class="input" type="number" min="1" name="default_min_purchase" value="<?= $settings['default_min_purchase'] ?? 1 ?>">
                    </div>
                    <p class="help">Valor padrão para novas rifas.</p>
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    <label class="label">Máximo de Números por Compra</label>
                    <div class="control">
                        <input class="input" type="number" min="1" name="default_max_purchase" value="<?= $settings['default_max_purchase'] ?? 10 ?>">
                    </div>
                    <p class="help">Valor padrão para novas rifas.</p>
                </div>
            </div>
        </div>
        
        <div class="field">
            <label class="checkbox">
                <input type="checkbox" name="auto_confirm_payment" value="1" <?= ($settings['auto_confirm_payment'] ?? false) ? 'checked' : '' ?>>
                <strong>Confirmar pagamentos automaticamente</strong> (via integração com gateway)
            </label>
        </div>
        
        <div class="field">
            <label class="checkbox">
                <input type="checkbox" name="send_email_notifications" value="1" <?= ($settings['send_email_notifications'] ?? true) ? 'checked' : '' ?>>
                <strong>Enviar notificações por email</strong> (pedidos, pagamentos, sorteios)
            </label>
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
<?= $this->endSection() ?>
