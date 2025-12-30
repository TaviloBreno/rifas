<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWinnersTable extends Migration
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
            'raffle_number_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'order_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'winning_number' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'winner_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'winner_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'winner_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'prize_position' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 1,
                'comment'    => '1 = 1º Prêmio, 2 = 2º Prêmio, etc',
            ],
            'prize_description' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'is_claimed' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'claimed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'draw_date' => [
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
        $this->forge->addForeignKey('raffle_id', 'raffles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('raffle_number_id', 'raffle_numbers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('winners');
    }

    public function down()
    {
        $this->forge->dropTable('winners');
    }
}
