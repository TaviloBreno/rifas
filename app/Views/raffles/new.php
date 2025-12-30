<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Nova Rifa<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="hero is-primary is-small">
    <div class="hero-body">
        <div class="container">
            <h1 class="title">
                <i class="fas fa-plus-circle"></i> Nova Rifa
            </h1>
            <p class="subtitle">
                Crie uma nova rifa e comece a vender!
            </p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-8">
                <div class="box">
                    <nav class="breadcrumb" aria-label="breadcrumbs">
                        <ul>
                            <li><a href="<?= site_url('raffles') ?>">Rifas</a></li>
                            <li class="is-active"><a href="#" aria-current="page">Nova Rifa</a></li>
                        </ul>
                    </nav>

                    <?php if (session()->has('errors')): ?>
                        <div class="notification is-danger is-light">
                            <button class="delete"></button>
                            <strong>Erro ao criar rifa:</strong>
                            <ul>
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('raffles') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="field">
                            <label class="label">Nome da Rifa *</label>
                            <div class="control has-icons-left">
                                <input class="input <?= session('errors.name') ? 'is-danger' : '' ?>" 
                                       type="text" 
                                       name="name" 
                                       placeholder="Ex: iPhone 15 Pro Max"
                                       value="<?= old('name') ?>"
                                       required>
                                <span class="icon is-small is-left">
                                    <i class="fas fa-ticket-alt"></i>
                                </span>
                            </div>
                            <?php if (session('errors.name')): ?>
                                <p class="help is-danger"><?= session('errors.name') ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="field">
                            <label class="label">Descrição</label>
                            <div class="control">
                                <textarea class="textarea <?= session('errors.description') ? 'is-danger' : '' ?>" 
                                          name="description" 
                                          placeholder="Descreva o prêmio e as regras da rifa..."
                                          rows="4"><?= old('description') ?></textarea>
                            </div>
                            <?php if (session('errors.description')): ?>
                                <p class="help is-danger"><?= session('errors.description') ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="columns">
                            <div class="column">
                                <div class="field">
                                    <label class="label">Preço por Número *</label>
                                    <div class="control has-icons-left">
                                        <input class="input <?= session('errors.price') ? 'is-danger' : '' ?>" 
                                               type="number" 
                                               name="price" 
                                               placeholder="10.00"
                                               step="0.01"
                                               min="0.01"
                                               value="<?= old('price') ?>"
                                               required>
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-dollar-sign"></i>
                                        </span>
                                    </div>
                                    <?php if (session('errors.price')): ?>
                                        <p class="help is-danger"><?= session('errors.price') ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="column">
                                <div class="field">
                                    <label class="label">Quantidade de Números *</label>
                                    <div class="control has-icons-left">
                                        <input class="input <?= session('errors.quantity') ? 'is-danger' : '' ?>" 
                                               type="number" 
                                               name="quantity" 
                                               placeholder="100"
                                               min="1"
                                               value="<?= old('quantity') ?>"
                                               required>
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-list-ol"></i>
                                        </span>
                                    </div>
                                    <?php if (session('errors.quantity')): ?>
                                        <p class="help is-danger"><?= session('errors.quantity') ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Data do Sorteio</label>
                            <div class="control has-icons-left">
                                <input class="input <?= session('errors.draw_date') ? 'is-danger' : '' ?>" 
                                       type="date" 
                                       name="draw_date"
                                       value="<?= old('draw_date') ?>">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                            </div>
                            <?php if (session('errors.draw_date')): ?>
                                <p class="help is-danger"><?= session('errors.draw_date') ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="field">
                            <label class="label">Imagem do Prêmio</label>
                            <div class="file has-name is-fullwidth">
                                <label class="file-label">
                                    <input class="file-input" type="file" name="image" accept="image/*" id="imageInput">
                                    <span class="file-cta">
                                        <span class="file-icon">
                                            <i class="fas fa-upload"></i>
                                        </span>
                                        <span class="file-label">
                                            Escolher arquivo…
                                        </span>
                                    </span>
                                    <span class="file-name" id="fileName">
                                        Nenhum arquivo selecionado
                                    </span>
                                </label>
                            </div>
                            <p class="help">Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB</p>
                            <?php if (session('errors.image')): ?>
                                <p class="help is-danger"><?= session('errors.image') ?></p>
                            <?php endif; ?>
                        </div>

                        <div id="imagePreview" class="mb-4" style="display: none;">
                            <figure class="image is-4by3" style="max-width: 300px;">
                                <img id="previewImg" src="" alt="Preview">
                            </figure>
                        </div>

                        <div class="field is-grouped">
                            <div class="control">
                                <button type="submit" class="button is-primary">
                                    <span class="icon">
                                        <i class="fas fa-save"></i>
                                    </span>
                                    <span>Criar Rifa</span>
                                </button>
                            </div>
                            <div class="control">
                                <a href="<?= site_url('raffles') ?>" class="button is-light">
                                    <span class="icon">
                                        <i class="fas fa-arrow-left"></i>
                                    </span>
                                    <span>Voltar</span>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Preview da imagem
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const fileName = document.getElementById('fileName');
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');

        if (file) {
            fileName.textContent = file.name;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            fileName.textContent = 'Nenhum arquivo selecionado';
            preview.style.display = 'none';
        }
    });

    // Fecha notificações
    document.querySelectorAll('.notification .delete').forEach(($delete) => {
        const $notification = $delete.parentNode;
        $delete.addEventListener('click', () => {
            $notification.parentNode.removeChild($notification);
        });
    });
</script>

<?= $this->endSection() ?>
