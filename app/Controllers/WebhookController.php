<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\RaffleNumberModel;
use App\Models\SettingModel;

class WebhookController extends BaseController
{
    protected OrderModel $orderModel;
    protected RaffleNumberModel $numberModel;
    protected SettingModel $settingModel;
    
    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->numberModel = new RaffleNumberModel();
        $this->settingModel = new SettingModel();
    }
    
    /**
     * Generic payment webhook
     */
    public function payment()
    {
        $input = $this->request->getJSON(true) ?? $this->request->getPost();
        
        // Log webhook
        log_message('info', 'Payment Webhook received: ' . json_encode($input));
        
        // Process based on gateway
        $gateway = $this->settingModel->get('payment_gateway');
        
        if ($gateway === 'mercadopago') {
            return $this->processMercadoPago($input);
        } elseif ($gateway === 'pagseguro') {
            return $this->processPagSeguro($input);
        }
        
        return $this->response->setJSON(['status' => 'ok']);
    }
    
    /**
     * Mercado Pago webhook
     */
    public function mercadopago()
    {
        $input = $this->request->getJSON(true);
        
        log_message('info', 'MercadoPago Webhook: ' . json_encode($input));
        
        return $this->processMercadoPago($input);
    }
    
    /**
     * PagSeguro webhook
     */
    public function pagseguro()
    {
        $notificationCode = $this->request->getPost('notificationCode');
        $notificationType = $this->request->getPost('notificationType');
        
        log_message('info', 'PagSeguro Webhook: ' . $notificationCode . ' - ' . $notificationType);
        
        if ($notificationType === 'transaction') {
            return $this->processPagSeguroNotification($notificationCode);
        }
        
        return $this->response->setJSON(['status' => 'ok']);
    }
    
    /**
     * Process Mercado Pago payment notification
     */
    protected function processMercadoPago($input)
    {
        if (!isset($input['type']) || $input['type'] !== 'payment') {
            return $this->response->setJSON(['status' => 'ignored']);
        }
        
        $paymentId = $input['data']['id'] ?? null;
        
        if (!$paymentId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No payment ID']);
        }
        
        // Get payment details from Mercado Pago API
        $accessToken = $this->settingModel->get('gateway_api_key');
        
        if (!$accessToken) {
            log_message('error', 'MercadoPago: No access token configured');
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not configured']);
        }
        
        $isSandbox = $this->settingModel->getBool('gateway_sandbox', false);
        $baseUrl = 'https://api.mercadopago.com';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl . '/v1/payments/' . $paymentId);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            log_message('error', 'MercadoPago API error: ' . $response);
            return $this->response->setJSON(['status' => 'error']);
        }
        
        $payment = json_decode($response, true);
        
        // Get order by external reference
        $orderCode = $payment['external_reference'] ?? null;
        
        if (!$orderCode) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No order reference']);
        }
        
        $order = $this->orderModel->where('order_code', $orderCode)->first();
        
        if (!$order) {
            log_message('error', 'MercadoPago: Order not found: ' . $orderCode);
            return $this->response->setJSON(['status' => 'error', 'message' => 'Order not found']);
        }
        
        // Check payment status
        if ($payment['status'] === 'approved') {
            // Confirm payment
            $this->orderModel->update($order['id'], [
                'status' => 'paid',
                'payment_method' => 'mercadopago',
                'transaction_id' => $paymentId,
                'paid_at' => date('Y-m-d H:i:s'),
            ]);
            
            // Mark numbers as sold
            $this->numberModel->confirmSale($order['id']);
            
            log_message('info', 'MercadoPago: Payment confirmed for order ' . $orderCode);
        } elseif (in_array($payment['status'], ['cancelled', 'rejected', 'refunded'])) {
            // Cancel order
            $this->orderModel->update($order['id'], [
                'status' => 'cancelled',
            ]);
            
            // Release numbers
            $this->numberModel->releaseNumbers($order['raffle_id'], $order['id']);
            
            log_message('info', 'MercadoPago: Order cancelled ' . $orderCode);
        }
        
        return $this->response->setJSON(['status' => 'ok']);
    }
    
    /**
     * Process PagSeguro notification
     */
    protected function processPagSeguroNotification($notificationCode)
    {
        $email = $this->settingModel->get('gateway_api_key');
        $token = $this->settingModel->get('gateway_secret_key');
        
        if (!$email || !$token) {
            log_message('error', 'PagSeguro: Not configured');
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not configured']);
        }
        
        $isSandbox = $this->settingModel->getBool('gateway_sandbox', false);
        $baseUrl = $isSandbox 
            ? 'https://ws.sandbox.pagseguro.uol.com.br'
            : 'https://ws.pagseguro.uol.com.br';
        
        $url = $baseUrl . '/v3/transactions/notifications/' . $notificationCode . '?email=' . $email . '&token=' . $token;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/xml',
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            log_message('error', 'PagSeguro API error: ' . $response);
            return $this->response->setJSON(['status' => 'error']);
        }
        
        $xml = simplexml_load_string($response);
        
        if (!$xml) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid XML']);
        }
        
        $orderCode = (string) $xml->reference;
        $status = (int) $xml->status;
        $transactionId = (string) $xml->code;
        
        $order = $this->orderModel->where('order_code', $orderCode)->first();
        
        if (!$order) {
            log_message('error', 'PagSeguro: Order not found: ' . $orderCode);
            return $this->response->setJSON(['status' => 'error', 'message' => 'Order not found']);
        }
        
        // PagSeguro statuses: 1=Aguardando, 2=Em análise, 3=Paga, 4=Disponível, 5=Em disputa, 6=Devolvida, 7=Cancelada
        if (in_array($status, [3, 4])) {
            // Payment approved
            $this->orderModel->update($order['id'], [
                'status' => 'paid',
                'payment_method' => 'pagseguro',
                'transaction_id' => $transactionId,
                'paid_at' => date('Y-m-d H:i:s'),
            ]);
            
            $this->numberModel->confirmSale($order['id']);
            
            log_message('info', 'PagSeguro: Payment confirmed for order ' . $orderCode);
        } elseif (in_array($status, [6, 7])) {
            // Cancelled or refunded
            $this->orderModel->update($order['id'], [
                'status' => 'cancelled',
            ]);
            
            $this->numberModel->releaseNumbers($order['raffle_id'], $order['id']);
            
            log_message('info', 'PagSeguro: Order cancelled ' . $orderCode);
        }
        
        return $this->response->setJSON(['status' => 'ok']);
    }
}
