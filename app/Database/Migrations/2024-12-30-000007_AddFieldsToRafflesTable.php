<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldsToRafflesTable extends Migration
{
    public function up()
    {
        $fields = [
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'name',
            ],
            'prize_description' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
                'after'      => 'description',
            ],
            'start_number' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
                'after'      => 'quantity',
            ],
            'min_per_purchase' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 1,
                'after'      => 'start_number',
            ],
            'max_per_purchase' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 100,
                'after'      => 'min_per_purchase',
            ],
            'numbers_generated' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'max_per_purchase',
            ],
            'winning_number' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'numbers_generated',
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'winning_number',
            ],
            'is_featured' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'status',
            ],
        ];

        $this->forge->addColumn('raffles', $fields);
        
        // Adicionar índice único para slug
        $this->db->query('CREATE UNIQUE INDEX slug_unique ON raffles(slug)');
    }

    public function down()
    {
        $this->forge->dropColumn('raffles', [
            'slug',
            'prize_description',
            'start_number',
            'min_per_purchase',
            'max_per_purchase',
            'numbers_generated',
            'winning_number',
            'user_id',
            'is_featured',
        ]);
    }
}
