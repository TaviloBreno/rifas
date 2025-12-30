<?php

namespace App\Services;

use App\Models\OrderModel;
use App\Models\RaffleEntryModel;
use App\Models\RaffleNumberModel;
use App\Models\TicketModel;

class TicketService
{
    public function __construct(
        protected TicketModel $ticketModel = new TicketModel(),
        protected RaffleEntryModel $entryModel = new RaffleEntryModel(),
        protected OrderModel $orderModel = new OrderModel(),
        protected RaffleNumberModel $raffleNumberModel = new RaffleNumberModel(),
    ) {
    }

    public function createFromOrder(int $orderId): int
    {
        $existing = $this->ticketModel->findByOrderId($orderId);
        if ($existing) {
            return (int) $existing->id;
        }

        $order = $this->orderModel->find($orderId);
        if (!$order) {
            throw new \RuntimeException('Pedido não encontrado.');
        }

        $code = strtoupper(bin2hex(random_bytes(6)));

        $ticketId = $this->ticketModel->insert([
            'order_id'  => $orderId,
            'user_id'   => $order->user_id ?: null,
            'raffle_id' => $order->raffle_id,
            'code'      => $code,
            'status'    => $order->status ?? 'pending',
            'expires_at'=> $order->expires_at ?: null,
        ], true);

        if (!$ticketId) {
            throw new \RuntimeException('Erro ao criar ticket.');
        }

        $numbers = $this->raffleNumberModel->getByOrder($orderId);

        foreach ($numbers as $number) {
            $this->entryModel->insert([
                'ticket_id'        => $ticketId,
                'raffle_id'         => (int) $order->raffle_id,
                'raffle_number_id'  => (int) $number->id,
                'number'            => (int) $number->number,
                'status'            => ($number->status === 'sold') ? 'sold' : 'reserved',
            ]);
        }

        return (int) $ticketId;
    }
}
