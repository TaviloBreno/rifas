<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<h1 class="title">Transferências</h1>

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

<div class="box">
  <form method="get" class="columns is-multiline">
    <div class="column is-3">
      <div class="field">
        <label class="label">Status</label>
        <div class="control">
          <div class="select is-fullwidth">
            <select name="status">
              <option value="" <?= ($filters['status'] ?? '') === '' ? 'selected' : '' ?>>Todos</option>
              <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pendente</option>
              <option value="processing" <?= ($filters['status'] ?? '') === 'processing' ? 'selected' : '' ?>>Processando</option>
              <option value="paid" <?= ($filters['status'] ?? '') === 'paid' ? 'selected' : '' ?>>Pago</option>
              <option value="failed" <?= ($filters['status'] ?? '') === 'failed' ? 'selected' : '' ?>>Falhou</option>
              <option value="cancelled" <?= ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelado</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="column is-3">
      <div class="field">
        <label class="label">Provider</label>
        <div class="control">
          <div class="select is-fullwidth">
            <select name="provider">
              <option value="" <?= ($filters['provider'] ?? '') === '' ? 'selected' : '' ?>>Todos</option>
              <option value="manual" <?= ($filters['provider'] ?? '') === 'manual' ? 'selected' : '' ?>>Manual</option>
              <option value="mercadopago" <?= ($filters['provider'] ?? '') === 'mercadopago' ? 'selected' : '' ?>>MercadoPago</option>
              <option value="asaas" <?= ($filters['provider'] ?? '') === 'asaas' ? 'selected' : '' ?>>Asaas</option>
              <option value="stripe" <?= ($filters['provider'] ?? '') === 'stripe' ? 'selected' : '' ?>>Stripe</option>
              <option value="pagseguro" <?= ($filters['provider'] ?? '') === 'pagseguro' ? 'selected' : '' ?>>PagSeguro</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="column is-3">
      <div class="field">
        <label class="label">Rifa (ID)</label>
        <div class="control">
          <input class="input" type="number" name="raffle_id" value="<?= esc((string) ($filters['raffle_id'] ?? '')) ?>" placeholder="ex: 12">
        </div>
      </div>
    </div>

    <div class="column is-3 is-flex is-align-items-flex-end">
      <div class="field">
        <div class="control">
          <button class="button is-primary is-fullwidth" type="submit">
            <span class="icon"><i class="fas fa-filter"></i></span>
            <span>Filtrar</span>
          </button>
        </div>
      </div>
    </div>
  </form>
</div>

<div class="box">
  <?php if (empty($transfers)): ?>
    <p>Nenhuma transferência encontrada.</p>
  <?php else: ?>
    <div class="table-container">
      <table class="table is-fullwidth is-striped is-hoverable">
        <thead>
          <tr>
            <th>ID</th>
            <th>Rifa</th>
            <th>Tipo</th>
            <th>Destino</th>
            <th>Valor</th>
            <th>Status</th>
            <th>Provider</th>
            <th>Criada em</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($transfers as $t): ?>
            <tr>
              <td><?= (int) $t->id ?></td>
              <td>
                <?= esc($t->raffle_name ?? ('#' . (int) $t->raffle_id)) ?>
              </td>
              <td><?= esc($t->recipient_type ?? '-') ?></td>
              <td>
                <?= esc($t->recipient_name ?? '-') ?><br>
                <small><?= esc($t->recipient_email ?? '-') ?></small>
              </td>
              <td>R$ <?= number_format((float) ($t->amount ?? 0), 2, ',', '.') ?></td>
              <td>
                <span class="tag <?= ($t->status ?? '') === 'paid' ? 'is-success' : (($t->status ?? '') === 'failed' ? 'is-danger' : 'is-warning') ?>">
                  <?= esc($t->status ?? '-') ?>
                </span>
              </td>
              <td><?= esc($t->provider ?? '-') ?></td>
              <td><small><?= esc($t->created_at ?? '-') ?></small></td>
              <td class="has-text-right">
                <a class="button is-small" href="<?= site_url('admin/transfers/' . (int) $t->id) ?>">
                  <span class="icon"><i class="fas fa-eye"></i></span>
                  <span>Ver</span>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?= $this->endSection() ?>
