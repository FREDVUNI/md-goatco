<?php

namespace App\Database\Seeders;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Use the Query Builder or Model
        $db = \Config\Database::connect();

        // ------------------------------------------------------------
        // 1. Insert roles (if your schema uses a separate roles table)
        // ------------------------------------------------------------
        $roles = [
            ['name' => 'super_admin', 'description' => 'Super Administrator'],
            ['name' => 'manager',     'description' => 'Farm Manager'],
            ['name' => 'vet',         'description' => 'Veterinarian'],
            ['name' => 'member',      'description' => 'Goat Owner / Member'],
        ];

        foreach ($roles as $role) {
            $db->table('roles')->insert($role);
        }

        // ------------------------------------------------------------
        // 2. Create a super admin user
        // ------------------------------------------------------------
        $db->table('users')->insert([
            'first_name' => 'Admin',
            'last_name'  => 'User',
            'email'      => 'admin@mdgoatco.com',
            'password'   => password_hash('password123', PASSWORD_DEFAULT),
            'role'       => 'super_admin',
            'status'     => 'active',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // ------------------------------------------------------------
        // 3. Create a manager user
        // ------------------------------------------------------------
        $db->table('users')->insert([
            'first_name' => 'John',
            'last_name'  => 'Manager',
            'email'      => 'manager@mdgoatco.com',
            'password'   => password_hash('password123', PASSWORD_DEFAULT),
            'role'       => 'manager',
            'status'     => 'active',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // ------------------------------------------------------------
        // 4. Create a vet user
        // ------------------------------------------------------------
        $db->table('users')->insert([
            'first_name' => 'Jane',
            'last_name'  => 'Vet',
            'email'      => 'vet@mdgoatco.com',
            'password'   => password_hash('password123', PASSWORD_DEFAULT),
            'role'       => 'vet',
            'status'     => 'active',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // ------------------------------------------------------------
        // 5. Create a couple of member users
        // ------------------------------------------------------------
        $members = [
            ['first_name' => 'Alice', 'last_name' => 'Member', 'email' => 'alice@example.com'],
            ['first_name' => 'Bob',   'last_name' => 'Owner',  'email' => 'bob@example.com'],
        ];

        foreach ($members as $m) {
            $db->table('users')->insert([
                'first_name' => $m['first_name'],
                'last_name'  => $m['last_name'],
                'email'      => $m['email'],
                'password'   => password_hash('password123', PASSWORD_DEFAULT),
                'role'       => 'member',
                'status'     => 'active',
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
