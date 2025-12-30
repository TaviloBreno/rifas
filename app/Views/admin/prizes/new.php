<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<nav class="breadcrumb" aria-label="breadcrumbs">
    <ul>
        <li><a href="<?= site_url('admin/raffles') ?>">Rifas</a></li>
        <li><a href="<?= site_url('admin/raffles/' . $raffle->id) ?>"><?= esc($raffle->title ?? $raffle->name ?? 'Rifa') ?></a></li>
        <li><a href="<?= site_url('admin/raffles/' . $raffle->id . '/prizes') ?>">Prêmios</a></li>
        <li class="is-active"><a href="#" aria-current="page">Novo</a></li>
    </ul>
</nav>

<?php if (session()->has('errors')): ?>
    <div class="notification is-danger is-light">
        <button class="delete" onclick="this.parentElement.remove()"></button>
        <strong>Erro ao criar prêmio:</strong>
        <ul>
            <?php foreach (session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="level">
    <div class="level-left">
        <div class="level-item">
            <h1 class="title">Novo Prêmio</h1>
        </div>
    </div>
    <div class="level-right">
        <div class="level-item">
            <a class="button is-light" href="<?= site_url('admin/raffles/' . $raffle->id . '/prizes') ?>">
                <span class="icon"><i class="fas fa-arrow-left"></i></span>
                <span>Voltar</span>
            </a>
        </div>
    </div>
</div>

<div class="box">
    <form action="<?= site_url('admin/raffles/' . $raffle->id . '/prizes') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="field">
            <label class="label">Nome do Prêmio *</label>
            <div class="control">
                <input class="input" type="text" name="name" value="<?= old('name') ?>" required>
            </div>
        </div>

        <div class="field">
            <label class="label">Descrição</label>
            <div class="control">
                <textarea class="textarea" name="description" rows="4"><?= old('description') ?></textarea>
            </div>
        </div>

        <div class="columns">
            <div class="column is-4">
                <div class="field">
                    <label class="label">Posição *</label>
                    <div class="control">
                        <input class="input" type="number" name="position" min="1" value="<?= old('position', $nextPosition ?? 1) ?>" required>
                    </div>
                    <p class="help">1 = 1º lugar, 2 = 2º lugar...</p>
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    <label class="label">Valor (opcional)</label>
                    <div class="control">
                        <input class="input" type="number" name="value" step="0.01" min="0" value="<?= old('value') ?>">
                    </div>
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    <label class="label">Imagem (opcional)</label>
                    <div class="file has-name is-fullwidth">
                        <label class="file-label">
                            <input class="file-input" type="file" name="image" id="imageInput" accept="image/*">
                            <span class="file-cta">
                                <span class="file-icon"><i class="fas fa-upload"></i></span>
                                <span class="file-label">Escolher arquivo…</span>
                            </span>
                            <span class="file-name" id="fileName">Nenhum arquivo selecionado</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div id="imagePreview" style="display:none;" class="mt-4">
            <p class="has-text-grey mb-2">Prévia:</p>
            <figure class="image is-4by3" style="max-width: 360px;">
                <img id="previewImg" src="" alt="Preview" style="object-fit: cover; border-radius: 4px;">
            </figure>
        </div>

        <div class="field is-grouped mt-5">
            <div class="control">
                <button type="submit" class="button is-success">
                    <span class="icon"><i class="fas fa-save"></i></span>
                    <span>Criar</span>
                </button>
            </div>
            <div class="control">
                <a class="button is-light" href="<?= site_url('admin/raffles/' . $raffle->id . '/prizes') ?>">Cancelar</a>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const fileName = document.getElementById('fileName');
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');

        if (file) {
            fileName.textContent = file.name;
            const reader = new FileReader();
            reader.onload = function(evt) {
                previewImg.src = evt.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            fileName.textContent = 'Nenhum arquivo selecionado';
            preview.style.display = 'none';
        }
    });
</script>

<?= $this->endSection() ?>
