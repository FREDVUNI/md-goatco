<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: 2026_06_20_000001_AddPaymentsTable
 *
 * Tracks Pesapal payment attempts (wallet top-ups) separately from the
 * immutable `transactions` ledger. A payment only becomes a `transactions`
 * credit row once Pesapal confirms it as COMPLETED — this table is the
 * working area for PENDING / FAILED / INVALID / REVERSED attempts and
 * keeps a full audit trail of what Pesapal told us and when.
 */
class AddPaymentsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'                  => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'member_id'           => ['type' => 'INT', 'unsigned' => true],

            // Our own reference, sent to Pesapal as `id` / merchant_reference.
            'merchant_reference'  => ['type' => 'VARCHAR', 'constraint' => 50],

            // Pesapal's own reference for this checkout, returned by SubmitOrderRequest.
            // Null until Pesapal accepts the order.
            'order_tracking_id'   => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],

            'amount'              => ['type' => 'BIGINT', 'unsigned' => true], // UGX, whole number
            'currency'            => ['type' => 'VARCHAR', 'constraint' => 10, 'default' => 'UGX'],
            'description'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],

            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'completed', 'failed', 'invalid', 'reversed'],
                'default'    => 'pending',
            ],

            // Populated once Pesapal confirms the transaction status.
            'payment_method'      => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],  // e.g. "Visa", "Mobile Money"
            'confirmation_code'   => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'raw_response'        => ['type' => 'TEXT', 'null' => true], // last raw JSON from Pesapal, for audit/debug

            // Once credited, links to the immutable ledger entry it created.
            'transaction_id'      => ['type' => 'INT', 'unsigned' => true, 'null' => true],

            'created_at'          => ['type' => 'DATETIME', 'null' => true],
            'updated_at'          => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('merchant_reference');
        $this->forge->addKey('order_tracking_id');
        $this->forge->addKey(['member_id', 'created_at']);
        $this->forge->addKey('status');
        $this->forge->addForeignKey('member_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('transaction_id', 'transactions', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('payments');
    }

    public function down(): void
    {
        $this->forge->dropTable('payments', true);
    }
}
