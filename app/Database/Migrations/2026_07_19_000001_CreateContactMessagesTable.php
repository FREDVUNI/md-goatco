<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateContactMessagesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name'         => ['type' => 'VARCHAR', 'constraint' => 150],
            'email'        => ['type' => 'VARCHAR', 'constraint' => 150],
            'phone'        => ['type' => 'VARCHAR', 'constraint' => 30],
            'subject'      => ['type' => 'VARCHAR', 'constraint' => 150],
            'message'      => ['type' => 'TEXT'],
            'status'       => ['type' => 'ENUM', 'constraint' => ['new', 'responded'], 'default' => 'new'],
            'responded_by' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'responded_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('status');
        $this->forge->addForeignKey('responded_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('contact_messages');
    }

    public function down()
    {
        $this->forge->dropTable('contact_messages');
    }
}
