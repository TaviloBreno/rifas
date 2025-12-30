<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Order;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Order::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'raffle_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_cpf',
        'total_numbers',
        'total_amount',
        'status',
        'payment_method',
        'payment_id',
        'paid_at',
        'expires_at',
        'notes',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'raffle_id'      => 'required|integer',
        'customer_name'  => 'required|min_length[3]|max_length[255]',
        'customer_email' => 'required|valid_email',
        'customer_phone' => 'required|min_length[10]|max_length[20]',
        'total_numbers'  => 'required|integer|greater_than[0]',
        'total_amount'   => 'required|decimal|greater_than[0]',
    ];

    protected $validationMessages = [
        'customer_name' => [
            'required'   => 'O nome é obrigatório.',
            'min_length' => 'O nome deve ter pelo menos 3 caracteres.',
        ],
        'customer_email' => [
            'required'    => 'O e-mail é obrigatório.',
            'valid_email' => 'Digite um e-mail válido.',
        ],
        'customer_phone' => [
            'required'   => 'O telefone é obrigatório.',
            'min_length' => 'Digite um telefone válido.',
        ],
    ];

    /**
     * Retorna pedidos por usuário
     */
    public function getByUser(int $userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Retorna pedidos por email
     */
    public function getByEmail(string $email)
    {
        return $this->where('customer_email', $email)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Retorna pedidos por rifa
     */
    public function getByRaffle(int $raffleId)
    {
        return $this->where('raffle_id', $raffleId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Retorna pedidos pendentes
     */
    public function getPending()
    {
        return $this->where('status', 'pending')
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Retorna pedidos pagos
     */
    public function getPaid()
    {
        return $this->where('status', 'paid')
                    ->orderBy('paid_at', 'DESC')
                    ->findAll();
    }

    /**
     * Marca pedido como pago
     */
    public function markAsPaid(int $orderId, string $paymentMethod = 'pix', string $paymentId = null): bool
    {
        return $this->update($orderId, [
            'status'         => 'paid',
            'payment_method' => $paymentMethod,
            'payment_id'     => $paymentId,
            'paid_at'        => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Marca pedido como cancelado
     */
    public function markAsCancelled(int $orderId, string $notes = null): bool
    {
        $data = ['status' => 'cancelled'];
        
        if ($notes) {
            $data['notes'] = $notes;
        }

        return $this->update($orderId, $data);
    }

    /**
     * Marca pedido como expirado
     */
    public function markAsExpired(int $orderId): bool
    {
        return $this->update($orderId, ['status' => 'expired']);
    }

    /**
     * Expira pedidos pendentes vencidos
     */
    public function expirePendingOrders(): int
    {
        $this->where('status', 'pending')
             ->where('expires_at <', date('Y-m-d H:i:s'))
             ->set(['status' => 'expired'])
             ->update();

        return $this->db->affectedRows();
    }

    /**
     * Conta pedidos por status
     */
    public function countByStatus(string $status = null): int
    {
        if ($status) {
            return $this->where('status', $status)->countAllResults();
        }

        return $this->countAllResults();
    }

    /**
     * Retorna total de vendas
     */
    public function getTotalSales(): float
    {
        $result = $this->selectSum('total_amount')
                       ->where('status', 'paid')
                       ->first();

        return (float) ($result->total_amount ?? 0);
    }

    /**
     * Retorna vendas por período
     */
    public function getSalesByPeriod(string $startDate, string $endDate): array
    {
        return $this->select('DATE(paid_at) as date, SUM(total_amount) as total, COUNT(*) as count')
                    ->where('status', 'paid')
                    ->where('paid_at >=', $startDate)
                    ->where('paid_at <=', $endDate)
                    ->groupBy('DATE(paid_at)')
                    ->orderBy('date', 'ASC')
                    ->findAll();
    }

    /**
     * Busca pedidos com detalhes da rifa
     */
    public function getWithRaffle(int $orderId)
    {
        return $this->select('orders.*, raffles.name as raffle_name, raffles.image as raffle_image')
                    ->join('raffles', 'raffles.id = orders.raffle_id')
                    ->where('orders.id', $orderId)
                    ->first();
    }

    /**
     * Lista pedidos com detalhes para admin
     */
    public function getForAdmin(array $filters = [])
    {
        $builder = $this->select('orders.*, raffles.name as raffle_name')
                        ->join('raffles', 'raffles.id = orders.raffle_id', 'left');

        if (!empty($filters['status'])) {
            $builder->where('orders.status', $filters['status']);
        }

        if (!empty($filters['raffle_id'])) {
            $builder->where('orders.raffle_id', $filters['raffle_id']);
        }

        if (!empty($filters['search'])) {
            $builder->groupStart()
                    ->like('orders.customer_name', $filters['search'])
                    ->orLike('orders.customer_email', $filters['search'])
                    ->orLike('orders.customer_phone', $filters['search'])
                    ->groupEnd();
        }

        return $builder->orderBy('orders.created_at', 'DESC')->findAll();
    }
}
