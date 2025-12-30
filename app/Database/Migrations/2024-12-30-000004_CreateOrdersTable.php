<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'raffle_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'customer_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'customer_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'customer_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'customer_cpf' => [
                'type'       => 'VARCHAR',
                'constraint' => 14,
                'null'       => true,
            ],
            'total_numbers' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'total_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'paid', 'cancelled', 'expired'],
                'default'    => 'pending',
            ],
            'payment_method' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'payment_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'paid_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('status');
        $this->forge->addKey('customer_email');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('raffle_id', 'raffles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('orders');

        // Adicionar FK de order_id na tabela raffle_numbers
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropTable('orders');
    }
}
