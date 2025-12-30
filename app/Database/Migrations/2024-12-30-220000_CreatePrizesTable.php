<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePrizesTable extends Migration
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
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'position' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
                'comment'    => '1 = 1º lugar, 2 = 2º lugar, etc',
            ],
            'value' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
                'comment'    => 'Valor estimado do prêmio',
            ],
            'winner_number' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'comment'    => 'Número sorteado vencedor',
            ],
            'winner_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'claimed_at' => [
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
        $this->forge->addForeignKey('raffle_id', 'raffles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('prizes');
    }

    public function down()
    {
        $this->forge->dropTable('prizes');
    }
}
