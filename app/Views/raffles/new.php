<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Nova Rifa<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
    .form-hero {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        padding: 5rem 0 3.5rem;
        margin-top: -2rem;
        position: relative;
        color: white;
    }
    
    .form-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .form-hero .container {
        position: relative;
        z-index: 2;
    }
    
    .hero-title {
        font-size: 2rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }
    
    .hero-subtitle {
        opacity: 0.9;
    }
    
    .form-main {
        margin-top: -2rem;
        position: relative;
        z-index: 10;
        padding-bottom: 3rem;
    }
    
    .form-card {
        background: white;
        border-radius: 25px;
        padding: 2rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    }
    
    .breadcrumb-custom {
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 12px;
    }
    
    .error-box {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        border: none;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .error-box strong {
        color: #721c24;
    }
    
    .error-box ul {
        margin-top: 0.5rem;
        margin-left: 1rem;
        color: #721c24;
    }
    
    .form-section {
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .section-title i {
        color: #28a745;
    }
    
    .field-label {
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0.5rem;
    }
    
    .modern-input {
        padding: 1rem 1rem 1rem 3rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .modern-input:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 4px rgba(40, 167, 69, 0.1);
    }
    
    .modern-input.is-danger {
        border-color: #dc3545;
    }
    
    .input-wrapper {
        position: relative;
    }
    
    .input-wrapper i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        z-index: 2;
    }
    
    .modern-textarea {
        padding: 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        min-height: 120px;
    }
    
    .modern-textarea:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 4px rgba(40, 167, 69, 0.1);
    }
    
    .field-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }
    
    .upload-area {
        border: 2px dashed #e0e0e0;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .upload-area:hover {
        border-color: #28a745;
        background: rgba(40, 167, 69, 0.05);
    }
    
    .upload-area.has-file {
        border-color: #28a745;
        background: rgba(40, 167, 69, 0.05);
    }
    
    .upload-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }
    
    .upload-icon i {
        font-size: 1.5rem;
        color: white;
    }
    
    .upload-text {
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0.25rem;
    }
    
    .upload-hint {
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    .preview-container {
        margin-top: 1rem;
        border-radius: 15px;
        overflow: hidden;
        max-width: 300px;
    }
    
    .preview-container img {
        width: 100%;
        border-radius: 15px;
    }
    
    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #f0f0f0;
    }
    
    .submit-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
    }
    
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 1.5rem;
        background: #f0f0f0;
        color: #495057;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .back-btn:hover {
        background: #e0e0e0;
        color: #1a1a2e;
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 1.5rem;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .submit-btn, .back-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<!-- Hero Section -->
<section class="form-hero">
    <div class="container">
        <h1 class="hero-title">
            <i class="fas fa-plus-circle"></i> Nova Rifa
        </h1>
        <p class="hero-subtitle">Crie uma nova rifa e comece a vender!</p>
    </div>
</section>

<!-- Main Content -->
<section class="section form-main">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-8">
                <div class="form-card">
                    <nav class="breadcrumb breadcrumb-custom" aria-label="breadcrumbs">
                        <ul>
                            <li><a href="<?= site_url('raffles') ?>"><i class="fas fa-ticket-alt mr-1"></i> Rifas</a></li>
                            <li class="is-active"><a href="#" aria-current="page">Nova Rifa</a></li>
                        </ul>
                    </nav>

                    <?php if (session()->has('errors')): ?>
                        <div class="error-box">
                            <strong><i class="fas fa-exclamation-circle mr-1"></i> Erro ao criar rifa:</strong>
                            <ul>
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('raffles') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <!-- Informações Básicas -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-info-circle"></i> Informações Básicas
                            </div>
                            
                            <div class="field">
                                <label class="field-label">Nome da Rifa *</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-ticket-alt"></i>
                                    <input class="input modern-input <?= session('errors.name') ? 'is-danger' : '' ?>" 
                                           type="text" 
                                           name="name" 
                                           placeholder="Ex: iPhone 15 Pro Max"
                                           value="<?= old('name') ?>"
                                           required>
                                </div>
                                <?php if (session('errors.name')): ?>
                                    <p class="help is-danger"><?= session('errors.name') ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="field">
                                <label class="field-label">Descrição</label>
                                <textarea class="textarea modern-textarea <?= session('errors.description') ? 'is-danger' : '' ?>" 
                                          name="description" 
                                          placeholder="Descreva o prêmio e as regras da rifa..."
                                          rows="4"><?= old('description') ?></textarea>
                                <?php if (session('errors.description')): ?>
                                    <p class="help is-danger"><?= session('errors.description') ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Valores e Quantidade -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-calculator"></i> Valores e Quantidade
                            </div>
                            
                            <div class="field-row">
                                <div class="field">
                                    <label class="field-label">Preço por Número *</label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-dollar-sign"></i>
                                        <input class="input modern-input <?= session('errors.price') ? 'is-danger' : '' ?>" 
                                               type="number" 
                                               name="price" 
                                               placeholder="10.00"
                                               step="0.01"
                                               min="0.01"
                                               value="<?= old('price') ?>"
                                               required>
                                    </div>
                                    <?php if (session('errors.price')): ?>
                                        <p class="help is-danger"><?= session('errors.price') ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="field">
                                    <label class="field-label">Quantidade de Números *</label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-list-ol"></i>
                                        <input class="input modern-input <?= session('errors.quantity') ? 'is-danger' : '' ?>" 
                                               type="number" 
                                               name="quantity" 
                                               placeholder="100"
                                               min="1"
                                               value="<?= old('quantity') ?>"
                                               required>
                                    </div>
                                    <?php if (session('errors.quantity')): ?>
                                        <p class="help is-danger"><?= session('errors.quantity') ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Data do Sorteio -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-calendar-alt"></i> Data do Sorteio
                            </div>
                            
                            <div class="field">
                                <label class="field-label">Data</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-calendar"></i>
                                    <input class="input modern-input <?= session('errors.draw_date') ? 'is-danger' : '' ?>" 
                                           type="date" 
                                           name="draw_date"
                                           value="<?= old('draw_date') ?>">
                                </div>
                                <?php if (session('errors.draw_date')): ?>
                                    <p class="help is-danger"><?= session('errors.draw_date') ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Imagem -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-image"></i> Imagem do Prêmio
                            </div>
                            
                            <label class="upload-area" id="uploadArea">
                                <input class="file-input" type="file" name="image" accept="image/*" id="imageInput" style="display: none;">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="upload-text" id="uploadText">Clique para selecionar uma imagem</div>
                                <div class="upload-hint">Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB</div>
                            </label>
                            <?php if (session('errors.image')): ?>
                                <p class="help is-danger"><?= session('errors.image') ?></p>
                            <?php endif; ?>
                            
                            <div id="imagePreview" class="preview-container" style="display: none;">
                                <img id="previewImg" src="" alt="Preview">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="submit-btn">
                                <i class="fas fa-save"></i> Criar Rifa
                            </button>
                            <a href="<?= site_url('raffles') ?>" class="back-btn">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    const uploadArea = document.getElementById('uploadArea');
    const imageInput = document.getElementById('imageInput');
    const uploadText = document.getElementById('uploadText');
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    uploadArea.addEventListener('click', () => imageInput.click());

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];

        if (file) {
            uploadText.textContent = file.name;
            uploadArea.classList.add('has-file');
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            uploadText.textContent = 'Clique para selecionar uma imagem';
            uploadArea.classList.remove('has-file');
            preview.style.display = 'none';
        }
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('has-file');
    });

    uploadArea.addEventListener('dragleave', () => {
        if (!imageInput.files.length) {
            uploadArea.classList.remove('has-file');
        }
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        imageInput.files = e.dataTransfer.files;
        imageInput.dispatchEvent(new Event('change'));
    });
</script>

<?= $this->endSection() ?>
