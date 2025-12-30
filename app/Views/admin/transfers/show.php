<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<h1 class="title">Transferência #<?= (int) $transfer->id ?></h1>

<div class="buttons">
  <a class="button" href="<?= site_url('admin/transfers') ?>">
    <span class="icon"><i class="fas fa-arrow-left"></i></span>
    <span>Voltar</span>
  </a>
</div>

<div class="columns">
  <div class="column is-6">
    <div class="box">
      <h2 class="subtitle">Resumo</h2>
      <table class="table is-fullwidth">
        <tbody>
          <tr><th>ID</th><td><?= (int) $transfer->id ?></td></tr>
          <tr><th>Rifa</th><td>#<?= (int) $transfer->raffle_id ?></td></tr>
          <tr><th>Winner ID</th><td><?= $transfer->winner_id ? (int) $transfer->winner_id : '-' ?></td></tr>
          <tr><th>Pedido</th><td><?= $transfer->order_id ? (int) $transfer->order_id : '-' ?></td></tr>
          <tr><th>Tipo</th><td><?= esc($transfer->recipient_type ?? '-') ?></td></tr>
          <tr><th>Destino</th><td><?= esc($transfer->recipient_name ?? '-') ?><br><small><?= esc($transfer->recipient_email ?? '-') ?></small></td></tr>
          <tr><th>Valor</th><td>R$ <?= number_format((float) ($transfer->amount ?? 0), 2, ',', '.') ?> (<?= esc($transfer->currency ?? 'BRL') ?>)</td></tr>
          <tr><th>Status</th><td><span class="tag"><?= esc($transfer->status ?? '-') ?></span></td></tr>
          <tr><th>Provider</th><td><?= esc($transfer->provider ?? '-') ?></td></tr>
          <tr><th>Referência</th><td><?= esc($transfer->provider_reference ?? '-') ?></td></tr>
          <tr><th>Processado em</th><td><?= esc($transfer->processed_at ?? '-') ?></td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="column is-6">
    <div class="box">
      <h2 class="subtitle">Dados sensíveis</h2>

      <div class="content">
        <p><strong>PIX Key Type:</strong> <?= esc($transfer->pix_key_type ?? '-') ?></p>
        <p><strong>PIX Key (decriptado):</strong> <?= $pixKey ? esc($pixKey) : '<em>não informado</em>' ?></p>
      </div>

      <hr>

      <h3 class="subtitle is-6">Payload do Provider (decriptado)</h3>
      <?php if (!$payload): ?>
        <p><em>Sem payload.</em></p>
      <?php else: ?>
        <pre style="white-space: pre-wrap;"><?php echo esc(json_encode($payload, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)); ?></pre>
      <?php endif; ?>

      <?php if (!empty($transfer->error_message)): ?>
        <hr>
        <div class="notification is-danger is-light">
          <strong>Erro:</strong>
          <div><?= esc($transfer->error_message) ?></div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
