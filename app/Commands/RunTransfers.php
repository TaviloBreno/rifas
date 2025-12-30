<?php

namespace App\Commands;

use App\Services\TransferService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class RunTransfers extends BaseCommand
{
    protected $group       = 'Transfers';
    protected $name        = 'transfers:run';
    protected $description = 'Cria (opcionalmente) e executa transferências pendentes de prêmios.';

    protected $usage = 'transfers:run [options]';

    protected $options = [
        '--queue'    => 'Cria transferências pendentes a partir dos ganhadores.',
        '--limit'    => 'Limite de itens para processar (padrão: 20).',
        '--dry-run'  => 'Simula (não grava nem executa).',
        '--no-notify'=> 'Não envia e-mails de notificação.',
    ];

    public function run(array $params)
    {
        $service = new TransferService();

        $limit = (int) (CLI::getOption('limit') ?? 20);
        $dryRun = (bool) CLI::getOption('dry-run');
        $queue = (bool) CLI::getOption('queue');
        $notify = !((bool) CLI::getOption('no-notify'));

        if ($queue) {
            if ($dryRun) {
                CLI::write('DRY-RUN: fila não será criada.');
            } else {
                $queued = $service->queueWinnerTransfers(max(20, $limit));
                CLI::write('Fila criada: ' . $queued['created'] . ' | Ignorados: ' . $queued['skipped']);
            }
        }

        $summary = $service->runPending($limit, $dryRun, $notify);

        CLI::write('Processadas: ' . $summary['processed']);
        CLI::write('Pagas: ' . $summary['paid']);
        CLI::write('Falhas: ' . $summary['failed']);
    }
}
