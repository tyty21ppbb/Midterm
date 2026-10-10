<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run()
    {
        $currentDateTime = date('Y-m-d H:i:s');

        // Create the default administrator.
        $this->call(UserSeeder::class);

        // Add sample products only if the products table is empty.
        $productCount = $this->db
            ->table('products')
            ->countAllResults();

        if ($productCount === 0) {
            $products = [
                [
                    'name'           => 'Mechanical Keyboard',
                    'price'          => 1899.00,
                    'stock_quantity' => 12,
                    'image'          => null,
                    'is_active'      => 1,
                    'created_at'     => $currentDateTime,
                    'updated_at'     => null,
                ],
                [
                    'name'           => 'Wireless Mouse',
                    'price'          => 799.00,
                    'stock_quantity' => 20,
                    'image'          => null,
                    'is_active'      => 1,
                    'created_at'     => $currentDateTime,
                    'updated_at'     => null,
                ],
                [
                    'name'           => 'USB-C Cable',
                    'price'          => 249.00,
                    'stock_quantity' => 35,
                    'image'          => null,
                    'is_active'      => 1,
                    'created_at'     => $currentDateTime,
                    'updated_at'     => null,
                ],
            ];

            $this->db
                ->table('products')
                ->insertBatch($products);
        }

        // Add sample customers only if the customers table is empty.
        $customerCount = $this->db
            ->table('customers')
            ->countAllResults();

        if ($customerCount === 0) {
            $customers = [
                [
                    'full_name' => 'Juan Dela Cruz',
                    'email'     => 'juan@example.com',
                    'phone'     => '09171234567',
                    'is_active' => 1,
                    'created_at' => $currentDateTime,
                    'updated_at' => null,
                ],
                [
                    'full_name' => 'Maria Santos',
                    'email'     => 'maria@example.com',
                    'phone'     => '09181234567',
                    'is_active' => 1,
                    'created_at' => $currentDateTime,
                    'updated_at' => null,
                ],
            ];

            $this->db
                ->table('customers')
                ->insertBatch($customers);
        }
    }
}
