<?php

namespace App\Commands;

use App\Services\RaffleDrawService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DrawRaffles extends BaseCommand
{
    protected $group       = 'Raffles';
    protected $name        = 'raffles:draw';
    protected $description = 'Realiza sorteios automaticamente para rifas elegíveis (draw_date<=hoje).';

    protected $usage = 'raffles:draw [options]';

    protected $options = [
        '--raffle'   => 'ID de uma rifa específica para sortear.',
        '--limit'    => 'Quantidade máxima de rifas para processar (padrão: 10).',
        '--dry-run'  => 'Simula o sorteio (não grava no banco).',
        '--no-email' => 'Não envia e-mails de notificação.',
    ];

    public function run(array $params)
    {
        $service = new RaffleDrawService();

        $raffleId = CLI::getOption('raffle');
        $limit = (int) (CLI::getOption('limit') ?? 10);
        $dryRun = (bool) CLI::getOption('dry-run');
        $sendEmail = !((bool) CLI::getOption('no-email'));

        if ($raffleId !== null && $raffleId !== '') {
            $result = $service->drawRaffle((int) $raffleId, $sendEmail, $dryRun);

            if (!$result['ok']) {
                CLI::error($result['message']);
                return;
            }

            $first = $result['winners'][0] ?? null;
            $number = $first ? (string) $first['winning_number'] : '';

            CLI::write($result['message']);
            if ($number !== '') {
                CLI::write('Número vencedor (1º prêmio): ' . $number);
            }

            CLI::write('Ganhadores criados: ' . count($result['winners']));
            if ($sendEmail && !$dryRun) {
                CLI::write('Emails enviados: ganhadores=' . ($result['emails']['winnerSent'] ?? 0) . ', criador=' . ($result['emails']['ownerSent'] ?? 0));
            }

            return;
        }

        $summary = $service->drawDueRaffles($limit, $sendEmail, $dryRun);

        CLI::write('Processadas: ' . $summary['processed']);
        CLI::write('Sorteadas: ' . $summary['drawn']);
        CLI::write('Ignoradas: ' . $summary['skipped']);

        foreach ($summary['results'] as $row) {
            $prefix = '#'.$row['raffle_id'].': ';
            if ($row['ok']) {
                CLI::write($prefix . 'OK (' . count($row['winners']) . ' ganhadores)');
            } else {
                CLI::write($prefix . 'SKIP - ' . $row['message']);
            }
        }
    }
}
