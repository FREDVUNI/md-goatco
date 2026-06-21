<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: 2026_06_15_000001_CreateCoreSchema
 *
 * Creates all tables for MD Goatco Farm in dependency order:
 *   1. users
 *   2. member_applications
 *   3. goats
 *   4. weight_logs
 *   5. vet_visits
 *   6. transactions
 *   7. notifications
 *   8. vet_schedules
 */
class CreateCoreSchema extends Migration
{
    public function up(): void
    {
        // ── 1. USERS ─────────────────────────────────────────────────────────
        $this->forge->addField([
            'id'                     => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'email'                  => ['type' => 'VARCHAR', 'constraint' => 255],
            'password_hash'          => ['type' => 'VARCHAR', 'constraint' => 255],
            'role'                   => ['type' => 'ENUM', 'constraint' => ['super_admin', 'manager', 'vet', 'member'], 'default' => 'member'],
            'status'                 => ['type' => 'ENUM', 'constraint' => ['active', 'inactive', 'pending', 'rejected'], 'default' => 'pending'],
            'first_name'             => ['type' => 'VARCHAR', 'constraint' => 100],
            'last_name'              => ['type' => 'VARCHAR', 'constraint' => 100],
            'phone'                  => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'created_by'             => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'last_login_at'          => ['type' => 'DATETIME', 'null' => true],
            'password_reset_token'   => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true],
            'password_reset_expires' => ['type' => 'DATETIME', 'null' => true],
            'refresh_token'          => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true],
            'created_at'             => ['type' => 'DATETIME', 'null' => true],
            'updated_at'             => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'             => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->addKey('role');
        $this->forge->addKey('status');
        $this->forge->createTable('users');

        // ── 2. MEMBER APPLICATIONS ────────────────────────────────────────────
        $this->forge->addField([
            'id'                  => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'             => ['type' => 'INT', 'unsigned' => true],
            // Personal
            'first_name'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'last_name'           => ['type' => 'VARCHAR', 'constraint' => 100],
            'dob'                 => ['type' => 'DATE', 'null' => true],
            'gender'              => ['type' => 'ENUM', 'constraint' => ['male', 'female', 'other'], 'null' => true],
            'phone'               => ['type' => 'VARCHAR', 'constraint' => 30],
            'address'             => ['type' => 'TEXT'],
            'occupation'          => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            // ID documents
            'nid_number'          => ['type' => 'VARCHAR', 'constraint' => 50],
            'nid_front_path'      => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'nid_back_path'       => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'photo_path'          => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            // Next of kin
            'nok_name'            => ['type' => 'VARCHAR', 'constraint' => 200],
            'nok_relationship'    => ['type' => 'VARCHAR', 'constraint' => 50],
            'nok_phone'           => ['type' => 'VARCHAR', 'constraint' => 30],
            'nok_address'         => ['type' => 'TEXT', 'null' => true],
            'nok_nid_number'      => ['type' => 'VARCHAR', 'constraint' => 50],
            'nok_nid_front_path'  => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'nok_nid_back_path'   => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            // Banking preferences
            'goats_requested'     => ['type' => 'VARCHAR', 'constraint' => 20],
            'notes'               => ['type' => 'TEXT', 'null' => true],
            // Review
            'status'              => ['type' => 'ENUM', 'constraint' => ['pending', 'approved', 'rejected', 'info_requested'], 'default' => 'pending'],
            'reviewed_by'         => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'reviewed_at'         => ['type' => 'DATETIME', 'null' => true],
            'rejection_reason'    => ['type' => 'TEXT', 'null' => true],
            'info_request_note'   => ['type' => 'TEXT', 'null' => true],
            'created_at'          => ['type' => 'DATETIME', 'null' => true],
            'updated_at'          => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('member_applications');

        // ── 3. GOATS ──────────────────────────────────────────────────────────
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tag_number' => ['type' => 'VARCHAR', 'constraint' => 50],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'breed'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'sex'        => ['type' => 'ENUM', 'constraint' => ['male', 'female'], 'null' => true],
            'dob'        => ['type' => 'DATE', 'null' => true],
            'member_id'  => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'pen_id'     => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'status'     => ['type' => 'ENUM', 'constraint' => ['active', 'sold', 'deceased', 'transferred'], 'default' => 'active'],
            'notes'      => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('tag_number');
        $this->forge->addKey('member_id');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('member_id', 'users', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('goats');

        // ── 4. WEIGHT LOGS ────────────────────────────────────────────────────
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'goat_id'    => ['type' => 'INT', 'unsigned' => true],
            'weight_kg'  => ['type' => 'DECIMAL', 'constraint' => '6,2'],
            'logged_by'  => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'logged_at'  => ['type' => 'DATETIME'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['goat_id', 'logged_at']);
        $this->forge->addForeignKey('goat_id', 'goats', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('weight_logs');

        // ── 5. VET VISITS ─────────────────────────────────────────────────────
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'goat_id'         => ['type' => 'INT', 'unsigned' => true],
            'vet_id'          => ['type' => 'INT', 'unsigned' => true],
            'visit_type'      => ['type' => 'VARCHAR', 'constraint' => 50],
            'visit_date'      => ['type' => 'DATETIME'],
            'temperature'     => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true],
            'weight_kg'       => ['type' => 'DECIMAL', 'constraint' => '6,2', 'null' => true],
            'medication'      => ['type' => 'TEXT', 'null' => true],
            'clinical_notes'  => ['type' => 'TEXT'],
            'is_flagged'      => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'flag_reason'     => ['type' => 'TEXT', 'null' => true],
            'followup_date'   => ['type' => 'DATE', 'null' => true],
            'flag_resolved_at'=> ['type' => 'DATETIME', 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['goat_id', 'visit_date']);
        $this->forge->addKey(['vet_id', 'is_flagged']);
        $this->forge->addForeignKey('goat_id', 'goats', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('vet_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('vet_visits');

        // ── 6. TRANSACTIONS ───────────────────────────────────────────────────
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'member_id'     => ['type' => 'INT', 'unsigned' => true],
            'type'          => ['type' => 'ENUM', 'constraint' => ['credit', 'debit']],
            'amount'        => ['type' => 'BIGINT', 'unsigned' => true],  // UGX in whole numbers
            'description'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'reference'     => ['type' => 'VARCHAR', 'constraint' => 30],
            'balance_after' => ['type' => 'BIGINT'],
            'created_by'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('reference');
        $this->forge->addKey(['member_id', 'created_at']);
        $this->forge->addForeignKey('member_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('transactions');

        // ── 7. NOTIFICATIONS ──────────────────────────────────────────────────
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'    => ['type' => 'INT', 'unsigned' => true],
            'title'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'body'       => ['type' => 'TEXT'],
            'type'       => ['type' => 'ENUM', 'constraint' => ['info', 'success', 'warning', 'alert'], 'default' => 'info'],
            'link'       => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'is_read'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_id', 'is_read']);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('notifications');

        // ── 8. VET SCHEDULES ─────────────────────────────────────────────────
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'task'            => ['type' => 'VARCHAR', 'constraint' => 255],
            'description'     => ['type' => 'TEXT', 'null' => true],
            'assigned_vet_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'animals_desc'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'scheduled_at'    => ['type' => 'DATETIME'],
            'completed_at'    => ['type' => 'DATETIME', 'null' => true],
            'status'          => ['type' => 'ENUM', 'constraint' => ['scheduled', 'in_progress', 'completed', 'cancelled'], 'default' => 'scheduled'],
            'created_by'      => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['assigned_vet_id', 'status']);
        $this->forge->addKey('scheduled_at');
        $this->forge->addForeignKey('assigned_vet_id', 'users', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('vet_schedules');
    }

    public function down(): void
    {
        // Drop in reverse order to respect foreign keys
        $this->forge->dropTable('vet_schedules',    true);
        $this->forge->dropTable('notifications',    true);
        $this->forge->dropTable('transactions',     true);
        $this->forge->dropTable('vet_visits',       true);
        $this->forge->dropTable('weight_logs',      true);
        $this->forge->dropTable('goats',            true);
        $this->forge->dropTable('member_applications', true);
        $this->forge->dropTable('users',            true);
    }
}
