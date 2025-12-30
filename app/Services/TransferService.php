<?php

namespace App\Services;

use App\Models\OrderModel;
use App\Models\PrizeModel;
use App\Models\RaffleModel;
use App\Models\TransferModel;
use App\Models\WinnerModel;

class TransferService
{
    public function __construct(
        protected TransferModel $transferModel = new TransferModel(),
        protected WinnerModel $winnerModel = new WinnerModel(),
        protected PrizeModel $prizeModel = new PrizeModel(),
        protected OrderModel $orderModel = new OrderModel(),
        protected RaffleModel $raffleModel = new RaffleModel(),
        protected EmailNotificationService $mailer = new EmailNotificationService(),
    ) {
    }

    /**
     * Cria transferências pendentes para ganhadores com prêmio que tenha valor.
     *
     * @return array{created:int,skipped:int}
     */
    public function queueWinnerTransfers(int $limit = 50): array
    {
        $limit = max(1, min(500, $limit));

        $winners = $this->winnerModel
            ->orderBy('draw_date', 'DESC')
            ->limit($limit)
            ->findAll();

        $created = 0;
        $skipped = 0;

        foreach ($winners as $winner) {
            $winnerId = (int) $winner->id;

            if ($this->transferModel->existsForWinner($winnerId)) {
                $skipped++;
                continue;
            }

            $raffleId = (int) $winner->raffle_id;
            $prizes = $this->prizeModel->getByRaffle($raffleId);
            $prizeValue = null;

            foreach ($prizes as $prize) {
                if ((int) ($prize->position ?? 0) === (int) ($winner->prize_position ?? 1)) {
                    $prizeValue = $prize->value ?? null;
                    break;
                }
            }

            $amount = $prizeValue !== null ? (float) $prizeValue : 0.0;
            if ($amount <= 0) {
                $skipped++;
                continue;
            }

            $order = $winner->order_id ? $this->orderModel->find((int) $winner->order_id) : null;

            // Melhor esforço para PIX key (prioridade: CPF do pedido, depois email, depois telefone)
            $pixKey = null;
            $pixKeyType = null;

            if ($order && !empty($order->customer_cpf)) {
                $pixKey = (string) $order->customer_cpf;
                $pixKeyType = 'cpf';
            } elseif (!empty($winner->winner_email)) {
                $pixKey = (string) $winner->winner_email;
                $pixKeyType = 'email';
            } elseif (!empty($winner->winner_phone)) {
                $pixKey = preg_replace('/\D+/', '', (string) $winner->winner_phone);
                $pixKeyType = 'phone';
            }

            $transfer = [
                'raffle_id'       => $raffleId,
                'winner_id'       => $winnerId,
                'order_id'        => $winner->order_id ? (int) $winner->order_id : null,
                'recipient_type'  => 'winner',
                'recipient_name'  => (string) ($winner->winner_name ?? null),
                'recipient_email' => (string) ($winner->winner_email ?? null),
                'pix_key_type'    => $pixKeyType,
                'amount'          => $amount,
                'currency'        => 'BRL',
                'provider'        => 'manual',
                'status'          => 'pending',
                'scheduled_at'    => date('Y-m-d H:i:s'),
            ];

            $transferId = $this->transferModel->insert($transfer, true);
            if ($transferId) {
                // criptografa pix key depois (para não salvar em claro)
                if ($pixKey) {
                    $entity = $this->transferModel->find((int) $transferId);
                    if ($entity) {
                        $entity->setPixKey($pixKey);
                        $this->transferModel->save($entity);
                    }
                }

                $created++;
            }
        }

        return ['created' => $created, 'skipped' => $skipped];
    }

    /**
     * Executa transferências pendentes.
     * No MVP, o provider "manual" apenas marca como pago e registra payload.
     *
     * @return array{processed:int,paid:int,failed:int}
     */
    public function runPending(int $limit = 20, bool $dryRun = false, bool $notify = true): array
    {
        $limit = max(1, min(200, $limit));

        $pending = $this->transferModel->getPending($limit);

        $processed = 0;
        $paid = 0;
        $failed = 0;

        foreach ($pending as $transfer) {
            $processed++;

            if ($dryRun) {
                continue;
            }

            $this->transferModel->update((int) $transfer->id, [
                'status' => 'processing',
                'error_message' => null,
            ]);

            try {
                if (($transfer->provider ?? 'manual') === 'manual') {
                    $payload = [
                        'mode' => 'manual',
                        'note' => 'Transferência marcada como paga (MVP). Configure um provedor real para automação.',
                    ];

                    $transfer->setProviderPayload($payload);

                    $this->transferModel->update((int) $transfer->id, [
                        'status' => 'paid',
                        'processed_at' => date('Y-m-d H:i:s'),
                        'provider_reference' => 'manual-' . (int) $transfer->id,
                        'provider_payload_encrypted' => $transfer->provider_payload_encrypted,
                    ]);

                    $paid++;

                    if ($notify) {
                        $this->notifyTransferPaid($transfer);
                    }

                    continue;
                }

                // Providers futuros
                throw new \RuntimeException('Provider não implementado: ' . (string) $transfer->provider);
            } catch (\Throwable $e) {
                $failed++;

                $this->transferModel->update((int) $transfer->id, [
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);

                log_message('error', 'Falha ao executar transferência {id}: {message}', [
                    'id' => (int) $transfer->id,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        return ['processed' => $processed, 'paid' => $paid, 'failed' => $failed];
    }

    private function notifyTransferPaid($transfer): void
    {
        $to = trim((string) ($transfer->recipient_email ?? ''));
        if ($to === '') {
            return;
        }

        $raffle = $this->raffleModel->find((int) $transfer->raffle_id);
        $raffleName = $raffle ? (string) ($raffle->name ?? 'Rifa') : 'Rifa';

        $amount = number_format((float) ($transfer->amount ?? 0), 2, ',', '.');

        $subject = 'Transferência do prêmio realizada';
        $html = '<p>Olá!</p>'
            . '<p>A transferência do seu prêmio foi registrada como <strong>PAGA</strong>.</p>'
            . '<p><strong>Rifa:</strong> ' . esc($raffleName) . '</p>'
            . '<p><strong>Valor:</strong> R$ ' . esc($amount) . '</p>'
            . '<p>Se precisar de ajuda, responda este e-mail.</p>';

        $this->mailer->send($to, $subject, $html);
    }
}
