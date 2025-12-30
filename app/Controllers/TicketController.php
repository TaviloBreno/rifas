<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\RaffleEntryModel;
use App\Models\RaffleModel;
use App\Models\TicketModel;

class TicketController extends BaseController
{
    public function confirm(int $ticketId)
    {
        $ticketModel = new TicketModel();
        $orderModel = new OrderModel();
        $raffleModel = new RaffleModel();
        $entryModel = new RaffleEntryModel();

        $ticket = $ticketModel->find($ticketId);
        if (!$ticket) {
            return redirect()->to('/')->with('error', 'Ticket não encontrado.');
        }

        $order = $ticket->order_id ? $orderModel->getWithRaffle((int) $ticket->order_id) : null;
        if (!$order) {
            return redirect()->to('/')->with('error', 'Pedido do ticket não encontrado.');
        }

        // Se expirou, reaproveita a regra já existente de expiração na tela de pagamento
        if ($order->isExpired() && $order->status === 'pending') {
            return redirect()->to("/pagamento/{$order->id}");
        }

        $raffle = $raffleModel->find($order->raffle_id);
        $entries = $entryModel->getByTicket($ticketId);

        return view('public/confirm_payment', [
            'ticket'  => $ticket,
            'order'   => $order,
            'raffle'  => $raffle,
            'entries' => $entries,
        ]);
    }

    public function processPayment(int $ticketId)
    {
        $ticketModel = new TicketModel();
        $orderModel = new OrderModel();

        $ticket = $ticketModel->find($ticketId);
        if (!$ticket) {
            return redirect()->to('/')->with('error', 'Ticket não encontrado.');
        }

        $order = $ticket->order_id ? $orderModel->find((int) $ticket->order_id) : null;
        if (!$order) {
            return redirect()->to('/')->with('error', 'Pedido do ticket não encontrado.');
        }

        $method = (string) $this->request->getPost('payment_method');
        if (!in_array($method, ['pix'], true)) {
            return redirect()->back()->with('error', 'Selecione uma forma de pagamento válida.');
        }

        // Guarda a opção no pedido e no ticket
        $orderModel->update($order->id, ['payment_method' => $method]);
        $ticketModel->update($ticketId, ['payment_method' => $method]);

        return redirect()->to("/pagamento/{$order->id}");
    }
}
