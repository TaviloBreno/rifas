<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<nav class="breadcrumb" aria-label="breadcrumbs">
    <ul>
        <li><a href="<?= site_url('admin/raffles') ?>">Rifas</a></li>
        <li class="is-active"><a href="#" aria-current="page"><?= isset($raffle) ? 'Editar' : 'Nova Rifa' ?></a></li>
    </ul>
</nav>

<h1 class="title"><?= isset($raffle) ? 'Editar Rifa' : 'Nova Rifa' ?></h1>

<?php if (session()->getFlashdata('error')): ?>
    <div class="notification is-danger is-light">
        <button class="delete" onclick="this.parentElement.remove()"></button>
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if (isset($errors) && !empty($errors)): ?>
    <div class="notification is-danger is-light">
        <button class="delete" onclick="this.parentElement.remove()"></button>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= isset($raffle) ? site_url('admin/raffles/' . $raffle->id) : site_url('admin/raffles') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <?php if (isset($raffle)): ?>
        <input type="hidden" name="_method" value="PUT">
    <?php endif; ?>
    
    <div class="box">
        <h2 class="subtitle">Informações Básicas</h2>
        
        <div class="columns">
            <div class="column is-8">
                <div class="field">
                    <label class="label">Título *</label>
                    <div class="control">
                        <input class="input" type="text" name="name" value="<?= old('name', $raffle->name ?? '') ?>" required placeholder="Ex: iPhone 15 Pro Max">
                    </div>
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    <label class="label">Status *</label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="status">
                                <option value="active" <?= old('status', $raffle->status ?? 'active') === 'active' ? 'selected' : '' ?>>Ativa</option>
                                <option value="finished" <?= old('status', $raffle->status ?? '') === 'finished' ? 'selected' : '' ?>>Finalizada</option>
                                <option value="cancelled" <?= old('status', $raffle->status ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelada</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="field">
            <label class="label">Descrição</label>
            <div class="control">
                <textarea class="textarea" name="description" rows="4" placeholder="Descrição detalhada da rifa..."><?= old('description', $raffle->description ?? '') ?></textarea>
            </div>
        </div>
        
        <div class="field">
            <label class="label">Descrição do Prêmio</label>
            <div class="control">
                <textarea class="textarea" name="prize_description" rows="3" placeholder="Detalhes do prêmio..."><?= old('prize_description', $raffle->prize_description ?? '') ?></textarea>
            </div>
        </div>
    </div>
    
    <div class="box">
        <h2 class="subtitle">Configuração de Números</h2>
        
        <div class="columns">
            <div class="column">
                <div class="field">
                    <label class="label">Preço por Número (R$) *</label>
                    <div class="control has-icons-left">
                        <input class="input" type="number" step="0.01" min="0.01" name="price" value="<?= old('price', $raffle->price ?? '') ?>" required placeholder="0,00">
                        <span class="icon is-small is-left">
                            <i class="fas fa-dollar-sign"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="field">
                    <label class="label">Total de Números *</label>
                    <div class="control has-icons-left">
                        <input class="input" type="number" min="1" name="quantity" value="<?= old('quantity', $raffle->quantity ?? 100) ?>" required <?= isset($raffle) ? 'readonly' : '' ?>>
                        <span class="icon is-small is-left">
                            <i class="fas fa-hashtag"></i>
                        </span>
                    </div>
                    <?php if (isset($raffle)): ?>
                        <p class="help">O total de números não pode ser alterado após a criação.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="column">
                <div class="field">
                    <label class="label">Número Inicial</label>
                    <div class="control has-icons-left">
                        <input class="input" type="number" min="0" name="start_number" value="<?= old('start_number', $raffle->start_number ?? 0) ?>" <?= isset($raffle) ? 'readonly' : '' ?>>
                        <span class="icon is-small is-left">
                            <i class="fas fa-play"></i>
                        </span>
                    </div>
                    <p class="help">Numeração começa a partir deste número.</p>
                </div>
            </div>
        </div>
        
        <div class="columns">
            <div class="column">
                <div class="field">
                    <label class="label">Mínimo por Compra</label>
                    <div class="control">
                        <input class="input" type="number" min="1" name="min_per_purchase" value="<?= old('min_per_purchase', $raffle->min_per_purchase ?? 1) ?>">
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="field">
                    <label class="label">Máximo por Compra</label>
                    <div class="control">
                        <input class="input" type="number" min="1" name="max_per_purchase" value="<?= old('max_per_purchase', $raffle->max_per_purchase ?? 10) ?>">
                    </div>
                    <p class="help">Deixe vazio para ilimitado.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="box">
        <h2 class="subtitle">Datas e Imagem</h2>
        
        <div class="columns">
            <div class="column">
            <div class="column">
                <div class="field">
                    <label class="label">Data do Sorteio</label>
                    <div class="control has-icons-left">
                        <input class="input" type="datetime-local" name="draw_date" value="<?= old('draw_date', isset($raffle->draw_date) ? date('Y-m-d\TH:i', strtotime($raffle->draw_date)) : '') ?>">
                        <span class="icon is-small is-left">
                            <i class="fas fa-trophy"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="field">
            <label class="label">Imagem da Rifa</label>
            <div class="file has-name is-fullwidth">
                <label class="file-label">
                    <input class="file-input" type="file" name="image" accept="image/*" onchange="updateFileName(this)">
                    <span class="file-cta">
                        <span class="file-icon">
                            <i class="fas fa-upload"></i>
                        </span>
                        <span class="file-label">Escolher arquivo...</span>
                    </span>
                    <span class="file-name" id="fileName">
                        <?php if (isset($raffle->image) && $raffle->image): ?>
                            <?= $raffle->image ?>
                        <?php else: ?>
                            Nenhum arquivo selecionado
                        <?php endif; ?>
                    </span>
                </label>
            </div>
            <?php if (isset($raffle->image) && $raffle->image): ?>
                <div class="mt-3">
                    <figure class="image is-128x128">
                        <img src="<?= base_url('uploads/' . $raffle->image) ?>" alt="Imagem atual" style="object-fit: cover; border-radius: 4px;">
                    </figure>
                    <p class="help">Imagem atual. Selecione um novo arquivo para substituir.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="field">
            <label class="checkbox">
                <input type="checkbox" name="is_featured" value="1" <?= old('is_featured', $raffle->is_featured ?? false) ? 'checked' : '' ?>>
                <strong>Destacar esta rifa</strong>
            </label>
            <p class="help">Rifas em destaque aparecem na página inicial.</p>
        </div>
    </div>
    
    <div class="field is-grouped">
        <div class="control">
            <button type="submit" class="button is-primary is-medium">
                <span class="icon"><i class="fas fa-save"></i></span>
                <span><?= isset($raffle) ? 'Salvar Alterações' : 'Criar Rifa' ?></span>
            </button>
        </div>
        <div class="control">
            <a href="<?= site_url('admin/raffles') ?>" class="button is-medium">Cancelar</a>
        </div>
    </div>
</form>

<script>
function updateFileName(input) {
    const fileName = input.files[0] ? input.files[0].name : 'Nenhum arquivo selecionado';
    document.getElementById('fileName').textContent = fileName;
}
</script>
<?= $this->endSection() ?>
