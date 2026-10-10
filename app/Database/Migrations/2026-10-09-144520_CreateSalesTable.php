<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSalesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'product_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'customer_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'sold_by' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'quantity' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'total_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'unsigned'   => true,
            ],

            'created_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('product_id');
        $this->forge->addKey('customer_id');
        $this->forge->addKey('sold_by');

        $this->forge->addForeignKey(
            'product_id',
            'products',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        $this->forge->addForeignKey(
            'customer_id',
            'customers',
            'id',
            'SET NULL',
            'RESTRICT'
        );

        $this->forge->addForeignKey(
            'sold_by',
            'users',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        $this->forge->createTable(
            'sales',
            true,
            ['ENGINE' => 'InnoDB']
        );
    }

    public function down()
    {
        $this->forge->dropTable('sales', true);
    }
}