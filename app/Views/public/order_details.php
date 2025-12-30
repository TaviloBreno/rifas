<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Pedido #<?= $order->id ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-8">
                <div class="box">
                    <!-- Status do Pedido -->
                    <div class="has-text-centered mb-5">
                        <?php if ($order->isPaid()): ?>
                            <span class="icon is-large has-text-success">
                                <i class="fas fa-4x fa-check-circle"></i>
                            </span>
                            <h1 class="title is-3 mt-3 has-text-success">Pagamento Confirmado!</h1>
                        <?php elseif ($order->isPending()): ?>
                            <span class="icon is-large has-text-warning">
                                <i class="fas fa-4x fa-clock"></i>
                            </span>
                            <h1 class="title is-3 mt-3 has-text-warning">Aguardando Pagamento</h1>
                            <a href="<?= site_url('pagamento/' . $order->id) ?>" class="button is-warning">
                                <i class="fas fa-qrcode mr-1"></i> Ver PIX para Pagamento
                            </a>
                        <?php elseif ($order->isCancelled()): ?>
                            <span class="icon is-large has-text-danger">
                                <i class="fas fa-4x fa-times-circle"></i>
                            </span>
                            <h1 class="title is-3 mt-3 has-text-danger">Pedido Cancelado</h1>
                        <?php else: ?>
                            <span class="icon is-large has-text-dark">
                                <i class="fas fa-4x fa-hourglass-end"></i>
                            </span>
                            <h1 class="title is-3 mt-3 has-text-dark">Pedido Expirado</h1>
                        <?php endif; ?>
                        
                        <p class="subtitle">Pedido #<?= str_pad($order->id, 6, '0', STR_PAD_LEFT) ?></p>
                    </div>

                    <hr>

                    <!-- Detalhes do Pedido -->
                    <div class="columns">
                        <div class="column">
                            <h4 class="title is-5"><i class="fas fa-info-circle"></i> Detalhes</h4>
                            <table class="table is-fullwidth">
                                <tr>
                                    <td><strong>Rifa:</strong></td>
                                    <td><?= esc($order->raffle_name) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Quantidade:</strong></td>
                                    <td><?= $order->total_numbers ?> número(s)</td>
                                </tr>
                                <tr>
                                    <td><strong>Valor Total:</strong></td>
                                    <td><span class="has-text-success has-text-weight-bold"><?= $order->getFormattedAmount() ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td><span class="tag <?= $order->getStatusClass() ?>"><?= $order->getStatusLabel() ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Data do Pedido:</strong></td>
                                    <td><?= $order->getFormattedCreatedAt() ?></td>
                                </tr>
                                <?php if ($order->isPaid()): ?>
                                <tr>
                                    <td><strong>Data do Pagamento:</strong></td>
                                    <td><?= $order->getFormattedPaidAt() ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                        
                        <div class="column">
                            <h4 class="title is-5"><i class="fas fa-user"></i> Comprador</h4>
                            <table class="table is-fullwidth">
                                <tr>
                                    <td><strong>Nome:</strong></td>
                                    <td><?= esc($order->customer_name) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>E-mail:</strong></td>
                                    <td><?= esc($order->customer_email) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Telefone:</strong></td>
                                    <td><?= $order->getFormattedPhone() ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Números -->
                    <?php if (!empty($numbers)): ?>
                    <div class="mt-4">
                        <h4 class="title is-5"><i class="fas fa-ticket-alt"></i> Seus Números</h4>
                        <div class="tags">
                            <?php foreach ($numbers as $number): ?>
                                <span class="tag is-medium <?= $order->isPaid() ? 'is-success' : ($order->isPending() ? 'is-warning' : 'is-light') ?>">
                                    <?= $number->getFormattedNumber($raffle->getNumberDigits()) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($order->isPaid()): ?>
                    <!-- Informações da Rifa -->
                    <div class="notification is-info is-light mt-4">
                        <h4 class="title is-6"><i class="fas fa-calendar"></i> Informações do Sorteio</h4>
                        <p><strong>Data do Sorteio:</strong> <?= $raffle->getFormattedDrawDate() ?></p>
                        <p class="mt-2">
                            Acompanhe o resultado na página da rifa ou consulte seus pedidos pelo e-mail cadastrado.
                        </p>
                    </div>
                    <?php endif; ?>

                    <!-- Botões -->
                    <div class="buttons is-centered mt-5">
                        <a href="<?= site_url('rifa/' . $raffle->slug) ?>" class="button is-info">
                            <span class="icon">
                                <i class="fas fa-eye"></i>
                            </span>
                            <span>Ver Rifa</span>
                        </a>
                        <a href="<?= site_url('meus-pedidos') ?>" class="button is-light">
                            <span class="icon">
                                <i class="fas fa-list"></i>
                            </span>
                            <span>Meus Pedidos</span>
                        </a>
                        <a href="<?= site_url('/') ?>" class="button is-primary">
                            <span class="icon">
                                <i class="fas fa-home"></i>
                            </span>
                            <span>Início</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
