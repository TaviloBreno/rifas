<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingsTable extends Migration
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
            'key' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['string', 'integer', 'boolean', 'json', 'text'],
                'default'    => 'string',
            ],
            'group' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'general',
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
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
        $this->forge->addUniqueKey('key');
        $this->forge->createTable('settings');

        // Inserir configurações padrão
        $settings = [
            // Geral
            [
                'key'         => 'site_name',
                'value'       => 'Rifas Online',
                'type'        => 'string',
                'group'       => 'general',
                'description' => 'Nome do site',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'key'         => 'site_description',
                'value'       => 'A melhor plataforma para criar e comprar rifas online',
                'type'        => 'string',
                'group'       => 'general',
                'description' => 'Descrição do site',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'key'         => 'contact_email',
                'value'       => 'contato@rifas.com',
                'type'        => 'string',
                'group'       => 'general',
                'description' => 'E-mail de contato',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'key'         => 'contact_phone',
                'value'       => '(11) 99999-9999',
                'type'        => 'string',
                'group'       => 'general',
                'description' => 'Telefone de contato',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            // Pagamento
            [
                'key'         => 'pix_key',
                'value'       => '',
                'type'        => 'string',
                'group'       => 'payment',
                'description' => 'Chave PIX para recebimento',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'key'         => 'pix_name',
                'value'       => '',
                'type'        => 'string',
                'group'       => 'payment',
                'description' => 'Nome do beneficiário PIX',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'key'         => 'pix_city',
                'value'       => 'SAO PAULO',
                'type'        => 'string',
                'group'       => 'payment',
                'description' => 'Cidade do beneficiário PIX',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'key'         => 'payment_expiration_minutes',
                'value'       => '30',
                'type'        => 'integer',
                'group'       => 'payment',
                'description' => 'Tempo de expiração do pagamento em minutos',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'key'         => 'auto_confirm_payment',
                'value'       => '0',
                'type'        => 'boolean',
                'group'       => 'payment',
                'description' => 'Confirmar pagamento automaticamente (via gateway)',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            // Rifas
            [
                'key'         => 'max_numbers_per_purchase',
                'value'       => '100',
                'type'        => 'integer',
                'group'       => 'raffle',
                'description' => 'Máximo de números por compra',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'key'         => 'min_numbers_per_purchase',
                'value'       => '1',
                'type'        => 'integer',
                'group'       => 'raffle',
                'description' => 'Mínimo de números por compra',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('settings')->insertBatch($settings);
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}
