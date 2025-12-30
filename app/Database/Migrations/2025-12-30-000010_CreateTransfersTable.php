<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransfersTable extends Migration
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
            'raffle_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'winner_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Vincula a um ganhador (quando aplicável)',
            ],
            'order_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'recipient_type' => [
                'type'       => 'ENUM',
                'constraint' => ['winner', 'creator'],
                'default'    => 'winner',
            ],
            'recipient_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'recipient_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'pix_key_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'comment'    => 'cpf|cnpj|email|phone|random',
            ],
            'pix_key_encrypted' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'currency' => [
                'type'       => 'CHAR',
                'constraint' => 3,
                'default'    => 'BRL',
            ],
            'provider' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'manual',
                'comment'    => 'manual|mercadopago|asaas|stripe|pagseguro',
            ],
            'provider_reference' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'provider_payload_encrypted' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'processing', 'paid', 'failed', 'cancelled'],
                'default'    => 'pending',
            ],
            'error_message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'scheduled_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'processed_at' => [
                'type' => 'DATETIME',
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
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('raffle_id');
        $this->forge->addKey('winner_id');
        $this->forge->addKey('order_id');
        $this->forge->addKey('status');
        $this->forge->addKey('provider');

        $this->forge->addForeignKey('raffle_id', 'raffles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('winner_id', 'winners', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('transfers');
    }

    public function down()
    {
        $this->forge->dropTable('transfers');
    }
}
