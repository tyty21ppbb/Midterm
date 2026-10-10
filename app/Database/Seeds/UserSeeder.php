<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Do not insert another administrator if the username already exists.
        $existingUser = $this->db
            ->table('users')
            ->where('username', 'admin')
            ->countAllResults();

        if ($existingUser > 0) {
            return;
        }

        $this->db->table('users')->insert([
            'username'   => 'admin',
            'full_name'  => 'System Administrator',
            'password'   => password_hash('Admin123!', PASSWORD_DEFAULT),
            'avatar'     => null,
            'is_active'  => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null,
        ]);
    }
}
