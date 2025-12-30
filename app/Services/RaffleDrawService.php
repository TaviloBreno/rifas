<?php

namespace App\Services;

use App\Models\OrderModel;
use App\Models\PrizeModel;
use App\Models\RaffleModel;
use App\Models\RaffleNumberModel;
use App\Models\UserModel;
use App\Models\WinnerModel;
use CodeIgniter\I18n\Time;

class RaffleDrawService
{
    public function __construct(
        protected RaffleModel $raffleModel = new RaffleModel(),
        protected RaffleNumberModel $raffleNumberModel = new RaffleNumberModel(),
        protected WinnerModel $winnerModel = new WinnerModel(),
        protected PrizeModel $prizeModel = new PrizeModel(),
        protected OrderModel $orderModel = new OrderModel(),
        protected UserModel $userModel = new UserModel(),
    ) {
    }

    /**
     * Realiza o sorteio de uma rifa (suporta múltiplos prêmios).
     *
     * @return array{ok:bool,message:string,winners:array<int,array>,emails:array<string,mixed>}
     */
    public function drawRaffle(int $raffleId, bool $sendEmail = true, bool $dryRun = false): array
    {
        $raffle = $this->raffleModel->find($raffleId);
        if (!$raffle) {
            return ['ok' => false, 'message' => 'Rifa não encontrada.', 'winners' => [], 'emails' => []];
        }

        if ($this->winnerModel->hasWinner($raffleId)) {
            return ['ok' => false, 'message' => 'Esta rifa já foi sorteada.', 'winners' => [], 'emails' => []];
        }

        $soldCount = $this->raffleNumberModel->countByStatus($raffleId, 'sold');
        if ($soldCount === 0) {
            return ['ok' => false, 'message' => 'Não há números vendidos para sortear.', 'winners' => [], 'emails' => []];
        }

        $prizes = $this->prizeModel->getByRaffle($raffleId);
        if (empty($prizes)) {
            // Fallback: mantém compatibilidade com o fluxo antigo quando não há registros em prizes
            $prizes = [
                (object) [
                    'id' => null,
                    'position' => 1,
                    'name' => (string) ($raffle->prize_description ?: 'Prêmio'),
                ],
            ];
        }

        $pickedRaffleNumberIds = [];
        $createdWinners = [];
        $emails = [
            'winnerSent' => 0,
            'ownerSent'  => 0,
            'skipped'    => [],
        ];

        $db = db_connect();
        $db->transStart();

        try {
            foreach ($prizes as $prize) {
                $winningNumber = $this->pickRandomSoldNumber($raffleId, $pickedRaffleNumberIds);
                if (!$winningNumber) {
                    // Sem mais números únicos suficientes
                    break;
                }

                $pickedRaffleNumberIds[] = (int) $winningNumber->id;

                $order = $this->orderModel->find($winningNumber->order_id);
                if (!$order) {
                    continue;
                }

                $winnerRow = [
                    'raffle_id'         => $raffleId,
                    'raffle_number_id'  => (int) $winningNumber->id,
                    'user_id'           => $winningNumber->user_id ? (int) $winningNumber->user_id : null,
                    'order_id'          => (int) $winningNumber->order_id,
                    'winning_number'    => (int) $winningNumber->number,
                    'winner_name'       => (string) $order->customer_name,
                    'winner_email'      => (string) $order->customer_email,
                    'winner_phone'      => (string) $order->customer_phone,
                    'prize_position'    => (int) ($prize->position ?? 1),
                    'prize_description' => (string) ($prize->name ?? $raffle->prize_description),
                    'draw_date'         => date('Y-m-d H:i:s'),
                ];

                if (!$dryRun) {
                    $this->winnerModel->insert($winnerRow);

                    if (!empty($prize->id)) {
                        $this->prizeModel->setWinner((int) $prize->id, (int) $winningNumber->number, (string) $order->customer_name);
                    }
                }

                $createdWinners[] = $winnerRow;
            }

            if (empty($createdWinners)) {
                $db->transComplete();
                return ['ok' => false, 'message' => 'Não foi possível sortear (nenhum ganhador criado).', 'winners' => [], 'emails' => []];
            }

            if (!$dryRun) {
                $this->raffleModel->update($raffleId, [
                    'status'         => 'finished',
                    'winning_number' => (int) $createdWinners[0]['winning_number'],
                ]);
            }

            $db->transComplete();
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Erro no sorteio da rifa {raffleId}: {message}', [
                'raffleId' => $raffleId,
                'message'  => $e->getMessage(),
            ]);

            return ['ok' => false, 'message' => 'Erro ao realizar o sorteio.', 'winners' => [], 'emails' => []];
        }

        if ($sendEmail && !$dryRun) {
            $emails = $this->sendDrawEmails($raffleId, $createdWinners);
        }

        return [
            'ok'      => true,
            'message' => 'Sorteio realizado com sucesso.',
            'winners' => $createdWinners,
            'emails'  => $emails,
        ];
    }

    /**
     * Sorteia rifas elegíveis (status=active, draw_date<=hoje, com números vendidos e sem winner).
     *
     * @return array{processed:int,drawn:int,skipped:int,results:array<int,array>}
     */
    public function drawDueRaffles(int $limit = 10, bool $sendEmail = true, bool $dryRun = false): array
    {
        $limit = max(1, min(200, $limit));

        $today = Time::today()->toDateString();

        $raffles = $this->raffleModel
            ->where('status', 'active')
            ->where('numbers_generated', 1)
            ->where('draw_date IS NOT NULL', null, false)
            ->where('draw_date <=', $today)
            ->orderBy('draw_date', 'ASC')
            ->limit($limit)
            ->findAll();

        $results = [];
        $processed = 0;
        $drawn = 0;
        $skipped = 0;

        foreach ($raffles as $raffle) {
            $processed++;

            $result = $this->drawRaffle((int) $raffle->id, $sendEmail, $dryRun);
            $results[] = array_merge(['raffle_id' => (int) $raffle->id], $result);

            if ($result['ok']) {
                $drawn++;
            } else {
                $skipped++;
            }
        }

        return [
            'processed' => $processed,
            'drawn'     => $drawn,
            'skipped'   => $skipped,
            'results'   => $results,
        ];
    }

    private function pickRandomSoldNumber(int $raffleId, array $excludeRaffleNumberIds = [])
    {
        $builder = $this->raffleNumberModel
            ->where('raffle_id', $raffleId)
            ->where('status', 'sold');

        if (!empty($excludeRaffleNumberIds)) {
            $builder->whereNotIn('id', $excludeRaffleNumberIds);
        }

        return $builder->orderBy('RAND()')->first();
    }

    /**
     * @param array<int,array> $winners
     */
    private function sendDrawEmails(int $raffleId, array $winners): array
    {
        $emails = [
            'winnerSent' => 0,
            'ownerSent'  => 0,
            'skipped'    => [],
        ];

        $raffle = $this->raffleModel->find($raffleId);
        if (!$raffle) {
            $emails['skipped'][] = 'raffle_not_found';
            return $emails;
        }

        $emailConfig = config('Email');
        if (empty($emailConfig->fromEmail)) {
            $emails['skipped'][] = 'from_email_not_configured';
            return $emails;
        }

        $email = service('email');
        $email->setMailType('html');

        // Email para o(s) ganhador(es)
        foreach ($winners as $winner) {
            $to = trim((string) ($winner['winner_email'] ?? ''));
            if ($to === '') {
                $emails['skipped'][] = 'winner_email_empty';
                continue;
            }

            $subject = 'Resultado do sorteio: ' . (string) ($raffle->name ?? 'Rifa');
            $body = $this->renderWinnerEmailHtml($raffle, $winner);

            $email->clear(true);
            $email->setFrom($emailConfig->fromEmail, $emailConfig->fromName ?: 'Rifas');
            $email->setTo($to);
            $email->setSubject($subject);
            $email->setMessage($body);

            if ($email->send(false)) {
                $emails['winnerSent']++;
            } else {
                log_message('warning', 'Falha ao enviar email para ganhador ({to}) da rifa {raffleId}.', [
                    'to' => $to,
                    'raffleId' => $raffleId,
                ]);
            }
        }

        // Email para o criador da rifa
        $owner = $raffle->user_id ? $this->userModel->find((int) $raffle->user_id) : null;
        $ownerEmail = $owner ? trim((string) $owner->email) : '';

        if ($ownerEmail !== '') {
            $subject = 'Sorteio realizado: ' . (string) ($raffle->name ?? 'Rifa');
            $body = $this->renderOwnerEmailHtml($raffle, $winners);

            $email->clear(true);
            $email->setFrom($emailConfig->fromEmail, $emailConfig->fromName ?: 'Rifas');
            $email->setTo($ownerEmail);
            $email->setSubject($subject);
            $email->setMessage($body);

            if ($email->send(false)) {
                $emails['ownerSent']++;
            } else {
                log_message('warning', 'Falha ao enviar email para criador ({to}) da rifa {raffleId}.', [
                    'to' => $ownerEmail,
                    'raffleId' => $raffleId,
                ]);
            }
        } else {
            $emails['skipped'][] = 'owner_email_empty';
        }

        return $emails;
    }

    private function renderWinnerEmailHtml($raffle, array $winner): string
    {
        $raffleName = esc((string) ($raffle->name ?? 'Rifa'));
        $number = esc((string) ($winner['winning_number'] ?? ''));
        $prize = esc((string) ($winner['prize_description'] ?? 'Prêmio'));
        $position = esc((string) ($winner['prize_position'] ?? '1'));

        return <<<HTML
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Resultado do Sorteio</title>
</head>
<body style="font-family: Arial, sans-serif; line-height:1.5;">
  <h2>Parabéns!</h2>
  <p>Você foi sorteado na rifa <strong>{$raffleName}</strong>.</p>
  <p><strong>{$position}º prêmio:</strong> {$prize}</p>
  <p><strong>Número vencedor:</strong> {$number}</p>
  <p>Guarde este e-mail como comprovante e entre em contato com o organizador para combinar a entrega.</p>
</body>
</html>
HTML;
    }

    /**
     * @param array<int,array> $winners
     */
    private function renderOwnerEmailHtml($raffle, array $winners): string
    {
        $raffleName = esc((string) ($raffle->name ?? 'Rifa'));
        $rows = '';

        foreach ($winners as $winner) {
            $pos = esc((string) ($winner['prize_position'] ?? '1'));
            $prize = esc((string) ($winner['prize_description'] ?? 'Prêmio'));
            $name = esc((string) ($winner['winner_name'] ?? ''));
            $email = esc((string) ($winner['winner_email'] ?? ''));
            $phone = esc((string) ($winner['winner_phone'] ?? ''));
            $number = esc((string) ($winner['winning_number'] ?? ''));

            $rows .= "<tr>";
            $rows .= "<td style=\"padding:8px;border:1px solid #ddd;\">{$pos}º</td>";
            $rows .= "<td style=\"padding:8px;border:1px solid #ddd;\">{$prize}</td>";
            $rows .= "<td style=\"padding:8px;border:1px solid #ddd;\">{$number}</td>";
            $rows .= "<td style=\"padding:8px;border:1px solid #ddd;\">{$name}</td>";
            $rows .= "<td style=\"padding:8px;border:1px solid #ddd;\">{$email}</td>";
            $rows .= "<td style=\"padding:8px;border:1px solid #ddd;\">{$phone}</td>";
            $rows .= "</tr>";
        }

        return <<<HTML
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Sorteio realizado</title>
</head>
<body style="font-family: Arial, sans-serif; line-height:1.5;">
  <h2>Sorteio realizado</h2>
  <p>O sorteio da rifa <strong>{$raffleName}</strong> foi realizado com sucesso.</p>
  <table style="border-collapse:collapse;width:100%;max-width:900px;">
    <thead>
      <tr>
        <th style="padding:8px;border:1px solid #ddd;text-align:left;">Posição</th>
        <th style="padding:8px;border:1px solid #ddd;text-align:left;">Prêmio</th>
        <th style="padding:8px;border:1px solid #ddd;text-align:left;">Número</th>
        <th style="padding:8px;border:1px solid #ddd;text-align:left;">Ganhador</th>
        <th style="padding:8px;border:1px solid #ddd;text-align:left;">Email</th>
        <th style="padding:8px;border:1px solid #ddd;text-align:left;">Telefone</th>
      </tr>
    </thead>
    <tbody>
      {$rows}
    </tbody>
  </table>
</body>
</html>
HTML;
    }
}
