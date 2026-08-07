<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTestimonialsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'quote'        => ['type' => 'TEXT'],
            'author_name'  => ['type' => 'VARCHAR', 'constraint' => 150],
            'author_role'  => ['type' => 'VARCHAR', 'constraint' => 150],
            'avatar_url'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'rating'       => ['type' => 'TINYINT', 'unsigned' => true, 'default' => 5],
            'sort_order'   => ['type' => 'INT', 'default' => 0],
            'is_active'    => ['type' => 'TINYINT', 'unsigned' => true, 'default' => 1],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['is_active', 'sort_order']);
        $this->forge->createTable('testimonials');

        // Seed the three testimonials the homepage already shipped with, so
        // switching this to admin-managed content doesn't blank the section.
        $now = date('Y-m-d H:i:s');
        $this->db->table('testimonials')->insertBatch([
            [
                'quote'       => "I joined Goat Banking with three goats. I can log in and see exactly how they're growing — no more waiting for a phone call.",
                'author_name' => 'Esther N.',
                'author_role' => 'Goat Banking member, Mukono',
                'avatar_url'  => 'https://images.pexels.com/photos/19911954/pexels-photo-19911954.jpeg?auto=compress&cs=tinysrgb&w=200',
                'rating'      => 5,
                'sort_order'  => 1,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'quote'       => "Vaccination records used to live in a notebook that could get lost. Now every goat's history is one search away.",
                'author_name' => 'Dr. Wasswa',
                'author_role' => 'Farm veterinarian',
                'avatar_url'  => 'https://images.pexels.com/photos/326929/pexels-photo-326929.jpeg?auto=compress&cs=tinysrgb&w=200',
                'rating'      => 5,
                'sort_order'  => 2,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'quote'       => 'Reports that took a full day in Excel now take minutes. We spend that time actually managing the herd.',
                'author_name' => 'Brian K.',
                'author_role' => 'Farm manager',
                'avatar_url'  => 'https://images.pexels.com/photos/25549225/pexels-photo-25549225.jpeg?auto=compress&cs=tinysrgb&w=200',
                'rating'      => 5,
                'sort_order'  => 3,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('testimonials');
    }
}
