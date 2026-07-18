<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOutcomeToVetVisits extends Migration
{
    public function up()
    {
        $this->forge->addColumn('vet_visits', [
            'outcome' => [
                'type'       => 'ENUM',
                'constraint' => ['healthy', 'monitoring', 'treated', 'critical'],
                'null'       => true,
                'after'      => 'clinical_notes',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('vet_visits', 'outcome');
    }
}
