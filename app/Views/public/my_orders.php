<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Meus Pedidos<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="hero is-primary is-small">
    <div class="hero-body">
        <div class="container">
            <h1 class="title">
                <i class="fas fa-receipt"></i> Meus Pedidos
            </h1>
            <p class="subtitle">Consulte seus pedidos pelo e-mail cadastrado</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <!-- Formulário de busca -->
        <div class="columns is-centered">
            <div class="column is-6">
                <div class="box">
                    <form action="<?= site_url('buscar-pedidos') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="field has-addons">
                            <div class="control is-expanded has-icons-left">
                                <input class="input is-medium" type="email" name="email" 
                                       placeholder="Digite seu e-mail" 
                                       value="<?= esc($email ?? '') ?>" required>
                                <span class="icon is-small is-left">
                                    <i class="fas fa-envelope"></i>
                                </span>
                            </div>
                            <div class="control">
                                <button type="submit" class="button is-primary is-medium">
                                    <i class="fas fa-search mr-1"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php if (isset($orders)): ?>
            <?php if (empty($orders)): ?>
                <div class="notification is-info has-text-centered">
                    <p><i class="fas fa-info-circle"></i> Nenhum pedido encontrado para este e-mail.</p>
                </div>
            <?php else: ?>
                <h2 class="title is-4 has-text-centered mt-5">
                    Encontrados <?= count($orders) ?> pedido(s)
                </h2>
                
                <div class="columns is-multiline">
                    <?php foreach ($orders as $order): ?>
                        <div class="column is-6">
                            <div class="box">
                                <div class="level is-mobile">
                                    <div class="level-left">
                                        <div>
                                            <p class="heading">Pedido</p>
                                            <p class="title is-5">#<?= str_pad($order->id, 6, '0', STR_PAD_LEFT) ?></p>
                                        </div>
                                    </div>
                                    <div class="level-right">
                                        <span class="tag is-medium <?= $order->getStatusClass() ?>">
                                            <i class="fas <?= $order->getStatusIcon() ?> mr-1"></i>
                                            <?= $order->getStatusLabel() ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="content">
                                    <p>
                                        <strong><i class="fas fa-ticket-alt"></i> Rifa:</strong> 
                                        <?= esc($order->raffle_name ?? 'Rifa #' . $order->raffle_id) ?>
                                    </p>
                                    <p>
                                        <strong><i class="fas fa-hashtag"></i> Números:</strong> 
                                        <?= $order->total_numbers ?> número(s)
                                    </p>
                                    <p>
                                        <strong><i class="fas fa-money-bill"></i> Valor:</strong> 
                                        <span class="has-text-success"><?= $order->getFormattedAmount() ?></span>
                                    </p>
                                    <p>
                                        <strong><i class="fas fa-calendar"></i> Data:</strong> 
                                        <?= $order->getFormattedCreatedAt() ?>
                                    </p>
                                </div>
                                
                                <div class="buttons">
                                    <a href="<?= site_url('pedido/' . $order->id) ?>" class="button is-info is-small">
                                        <i class="fas fa-eye mr-1"></i> Ver Detalhes
                                    </a>
                                    <?php if ($order->isPending()): ?>
                                        <a href="<?= site_url('pagamento/' . $order->id) ?>" class="button is-success is-small">
                                            <i class="fas fa-qrcode mr-1"></i> Pagar
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>
