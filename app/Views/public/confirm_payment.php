<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Confirmação - Pedido #<?= $order->id ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
    .confirm-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 4.5rem 0 3.5rem;
        margin-top: -2rem;
        position: relative;
        text-align: center;
        color: white;
    }

    .confirm-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .confirm-main {
        margin-top: -2.5rem;
        position: relative;
        z-index: 10;
        padding-bottom: 3rem;
    }

    .confirm-card {
        background: white;
        border-radius: 22px;
        padding: 2rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }

    .pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255,255,255,0.18);
        padding: 0.6rem 1.2rem;
        border-radius: 999px;
        backdrop-filter: blur(10px);
        font-weight: 700;
    }

    .numbers-box {
        background: #f8f9fa;
        border-radius: 16px;
        padding: 1.25rem;
        margin-top: 1.25rem;
    }

    .numbers-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 44px;
        height: 36px;
        padding: 0 0.75rem;
        border-radius: 10px;
        background: white;
        border: 1px solid #e9ecef;
        font-weight: 800;
        color: #1a1a2e;
    }

    .pay-options {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.75rem;
        margin-top: 1.25rem;
    }

    .pay-option {
        border: 2px solid #e9ecef;
        border-radius: 16px;
        padding: 1rem 1rem;
        cursor: pointer;
        display: flex;
        gap: 0.75rem;
        align-items: center;
        transition: all .2s ease;
    }

    .pay-option:hover {
        transform: translateY(-1px);
        border-color: #667eea;
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.15);
    }

    .pay-option input {
        transform: scale(1.2);
    }

    .pay-title {
        font-weight: 800;
    }

    .pay-desc {
        color: #6c757d;
        font-size: 0.95rem;
        margin-top: 0.15rem;
    }

    .actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        justify-content: space-between;
        margin-top: 1.5rem;
    }

    .btn {
        border: none;
        border-radius: 14px;
        padding: 0.95rem 1.25rem;
        font-weight: 800;
        cursor: pointer;
        transition: all .2s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(40, 167, 69, 0.25);
    }

    .btn-secondary {
        background: #f1f3f5;
        color: #1a1a2e;
    }

    .btn-secondary:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.06);
    }

    .amount {
        font-size: 2.25rem;
        font-weight: 900;
        color: #155724;
    }

    @media (min-width: 992px) {
        .pay-options { grid-template-columns: 1fr 1fr; }
    }
</style>

<section class="confirm-hero">
    <div class="container">
        <h1 style="font-weight: 900;">Confirme seu pedido</h1>
        <div class="pill">
            <span>Pedido #<?= $order->id ?></span>
            <span style="opacity:.7;">•</span>
            <span>Ticket <?= esc($ticket->code) ?></span>
        </div>
    </div>
</section>

<section class="confirm-main">
    <div class="container">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="notification is-danger is-light">
                <button class="delete" onclick="this.parentElement.remove()"></button>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="confirm-card">
            <div class="columns is-vcentered">
                <div class="column is-7">
                    <h2 class="title is-4 mb-2">Rifa</h2>
                    <p class="subtitle is-6 mb-0"><?= esc($order->raffle_name ?? ($raffle->name ?? 'Rifa')) ?></p>

                    <div class="numbers-box">
                        <p style="font-weight: 800; margin-bottom: .75rem;">Números selecionados</p>
                        <div class="numbers-grid">
                            <?php foreach ($entries as $entry): ?>
                                <span class="num"><?= str_pad((int) $entry->number, 4, '0', STR_PAD_LEFT) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="column is-5">
                    <h2 class="title is-4 mb-2">Total</h2>
                    <div class="amount">R$ <?= number_format((float) $order->total_amount, 2, ',', '.') ?></div>
                    <p class="has-text-grey mt-2">Escolha uma forma de pagamento para continuar.</p>

                    <form method="post" action="<?= site_url('confirmacao/' . $ticket->id . '/pagamento') ?>" class="mt-4">
                        <?= csrf_field() ?>

                        <div class="pay-options">
                            <label class="pay-option">
                                <input type="radio" name="payment_method" value="pix" checked>
                                <div>
                                    <div class="pay-title">PIX</div>
                                    <div class="pay-desc">Pague via QR Code ou Copia e Cola.</div>
                                </div>
                            </label>

                            <div class="pay-option" style="opacity: .5; cursor: not-allowed;">
                                <input type="radio" disabled>
                                <div>
                                    <div class="pay-title">Cartão</div>
                                    <div class="pay-desc">Em breve.</div>
                                </div>
                            </div>
                        </div>

                        <div class="actions">
                            <a class="btn btn-secondary" href="<?= site_url('checkout/' . ($raffle->slug ?? $raffle->id)) ?>">Voltar</a>
                            <button type="submit" class="btn btn-primary">Ir para pagamento</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <p class="has-text-grey mt-4" style="text-align:center;">Ao continuar, seus números permanecem reservados até o vencimento do pagamento.</p>
    </div>
</section>

<?= $this->endSection() ?>
