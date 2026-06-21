<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * DatabaseSeeder
 *
 * Seeds a realistic demo dataset: super admin, staff, Goat Banking
 * members in every application state, a working herd, vet history,
 * a vet schedule, wallet transactions, sample Pesapal payments, and
 * notifications — enough to click through every screen in the app
 * without starting from an empty database.
 *
 * Run with:
 *   php spark db:seed DatabaseSeeder
 *
 * Safe to re-run on a fresh database only — re-running against data
 * that already exists will fail on unique constraints (email,
 * tag_number, etc). Use `php spark migrate:refresh` first if you
 * need to reset.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(AdminSeeder::class);
        $this->call(StaffSeeder::class);
        $this->call(MemberSeeder::class);
        $this->call(GoatSeeder::class);
        $this->call(WeightLogSeeder::class);
        $this->call(VetVisitSeeder::class);
        $this->call(VetScheduleSeeder::class);
        $this->call(TransactionSeeder::class);
        $this->call(PaymentSeeder::class);
        $this->call(NotificationSeeder::class);

        echo "\n✓ Database seeded successfully.\n";
        echo "  See README.md for the full list of demo login credentials.\n";
    }
}

// ══════════════════════════════════════════════════════════════════════════════
// SUPER ADMIN
// ══════════════════════════════════════════════════════════════════════════════

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('users')->insert([
            'email'         => 'admin@mdgoatco.farm',
            'password_hash' => password_hash('Admin@2026!', PASSWORD_BCRYPT, ['cost' => 12]),
            'role'          => 'super_admin',
            'status'        => 'active',
            'first_name'    => 'Super',
            'last_name'     => 'Admin',
            'created_at'    => $now,
            'updated_at'    => $now,
        ]);

        echo "✓ Super admin seeded: admin@mdgoatco.farm / Admin@2026!\n";
        echo "  ⚠️  CHANGE THIS PASSWORD IMMEDIATELY after first login.\n";
    }
}

// ══════════════════════════════════════════════════════════════════════════════
// STAFF — managers & vets
// ══════════════════════════════════════════════════════════════════════════════

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = $this->db->table('users')->where('email', 'admin@mdgoatco.farm')->get()->getRow()->id;
        $now     = date('Y-m-d H:i:s');

        $staff = [
            [
                'email'         => 'manager@mdgoatco.farm',
                'password_hash' => password_hash('Manager@2026!', PASSWORD_BCRYPT, ['cost' => 12]),
                'role'          => 'manager',
                'status'        => 'active',
                'first_name'    => 'Brian',
                'last_name'     => 'Kato',
                'created_by'    => $adminId,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'email'         => 'grace.farm@mdgoatco.farm',
                'password_hash' => password_hash('Manager@2026!', PASSWORD_BCRYPT, ['cost' => 12]),
                'role'          => 'manager',
                'status'        => 'active',
                'first_name'    => 'Grace',
                'last_name'     => 'Kobusingye',
                'created_by'    => $adminId,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'email'         => 'vet@mdgoatco.farm',
                'password_hash' => password_hash('Vet@2026!', PASSWORD_BCRYPT, ['cost' => 12]),
                'role'          => 'vet',
                'status'        => 'active',
                'first_name'    => 'Dr. Wasswa',
                'last_name'     => 'Muyanja',
                'created_by'    => $adminId,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'email'         => 'dr.namatovu@mdgoatco.farm',
                'password_hash' => password_hash('Vet@2026!', PASSWORD_BCRYPT, ['cost' => 12]),
                'role'          => 'vet',
                'status'        => 'active',
                'first_name'    => 'Dr. Sandra',
                'last_name'     => 'Namatovu',
                'created_by'    => $adminId,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ];

        $this->db->table('users')->insertBatch($staff);
        echo "✓ Staff seeded: 2 managers + 2 vets\n";
    }
}

// ══════════════════════════════════════════════════════════════════════════════
// MEMBERS — covers every application status: approved, pending,
// info_requested, rejected — so the admin Applications queue isn't empty.
// ══════════════════════════════════════════════════════════════════════════════

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = $this->db->table('users')->where('email', 'admin@mdgoatco.farm')->get()->getRow()->id;
        $now     = date('Y-m-d H:i:s');
        $approved = [
            ['Esther',   'Nakato',     'female', 34, 'Mukono Town',        'Primary School Teacher', '2'],
            ['Robert',   'Kizito',     'male',   41, 'Goma, Mukono',       'Boda Boda Operator',     '1'],
            ['Grace',    'Namuli',     'female', 29, 'Seeta, Mukono',      'Tailor',                 '3'],
            ['Peter',    'Ssempala',   'male',   52, 'Kayunga Road',       'Retired Civil Servant',  '1'],
            ['Joyce',    'Auma',       'female', 38, 'Nakifuma',           'Shop Owner',              '2'],
            ['David',    'Okello',     'male',   45, 'Mukono Town',        'Driver',                 '1'],
            ['Sarah',    'Nansubuga',  'female', 31, 'Goma, Mukono',       'Nurse',                  '2'],
            ['Moses',    'Tumusiime',  'male',   27, 'Seeta, Mukono',      'IT Technician',          '1'],
        ];

        foreach ($approved as $i => [$first, $last, $gender, $age, $address, $occupation, $goatsRequested]) {
            $email = strtolower($first) . '.' . strtolower($last) . '@example.com';
            $dob   = date('Y-m-d', strtotime('-' . $age . ' years -' . rand(0, 300) . ' days'));
            $joinedDaysAgo = rand(40, 240);
            $createdAt = date('Y-m-d H:i:s', strtotime('-' . $joinedDaysAgo . ' days'));

            $this->db->table('users')->insert([
                'email'         => $email,
                'password_hash' => password_hash('Member@2026!', PASSWORD_BCRYPT, ['cost' => 12]),
                'role'          => 'member',
                'status'        => 'active',
                'first_name'    => $first,
                'last_name'     => $last,
                'phone'         => '+2567' . rand(10000000, 99999999),
                'last_login_at' => date('Y-m-d H:i:s', strtotime('-' . rand(0, 10) . ' days')),
                'created_at'    => $createdAt,
                'updated_at'    => $createdAt,
            ]);
            $userId = $this->db->insertID();

            $this->db->table('member_applications')->insert([
                'user_id'          => $userId,
                'first_name'       => $first,
                'last_name'        => $last,
                'dob'              => $dob,
                'gender'           => $gender,
                'phone'            => '+2567' . rand(10000000, 99999999),
                'address'          => $address . ', Mukono District',
                'occupation'       => $occupation,
                'nid_number'       => 'CM' . rand(70, 99) . rand(100000, 999999) . chr(rand(65, 90)) . chr(rand(65, 90)),
                'nok_name'         => $this->randomNokName(),
                'nok_relationship' => ['spouse', 'sibling', 'parent', 'child'][array_rand(['spouse', 'sibling', 'parent', 'child'])],
                'nok_phone'        => '+2567' . rand(10000000, 99999999),
                'nok_address'      => $address . ', Mukono District',
                'nok_nid_number'   => 'CM' . rand(70, 99) . rand(100000, 999999) . chr(rand(65, 90)) . chr(rand(65, 90)),
                'goats_requested'  => $goatsRequested,
                'notes'            => null,
                'status'           => 'approved',
                'reviewed_by'      => $adminId,
                'reviewed_at'      => date('Y-m-d H:i:s', strtotime($createdAt . ' +2 days')),
                'created_at'       => $createdAt,
                'updated_at'       => $createdAt,
            ]);
        }

        echo "✓ 8 approved Goat Banking members seeded\n";

        // ── 2 pending applications ──────────────────────────────────────────
        $pending = [
            ['Patricia', 'Achieng', 'female', 36, 'Nakifuma', 'Market Vendor', '2'],
            ['John',     'Mukasa',  'male',   48, 'Mukono Town', 'Carpenter', '1'],
        ];

        foreach ($pending as [$first, $last, $gender, $age, $address, $occupation, $goatsRequested]) {
            $email = strtolower($first) . '.' . strtolower($last) . '@example.com';
            $dob   = date('Y-m-d', strtotime('-' . $age . ' years'));
            $createdAt = date('Y-m-d H:i:s', strtotime('-' . rand(1, 5) . ' days'));

            $this->db->table('users')->insert([
                'email'         => $email,
                'password_hash' => password_hash('Member@2026!', PASSWORD_BCRYPT, ['cost' => 12]),
                'role'          => 'member',
                'status'        => 'pending',
                'first_name'    => $first,
                'last_name'     => $last,
                'phone'         => '+2567' . rand(10000000, 99999999),
                'created_at'    => $createdAt,
                'updated_at'    => $createdAt,
            ]);
            $userId = $this->db->insertID();

            $this->db->table('member_applications')->insert([
                'user_id'          => $userId,
                'first_name'       => $first,
                'last_name'        => $last,
                'dob'              => $dob,
                'gender'           => $gender,
                'phone'            => '+2567' . rand(10000000, 99999999),
                'address'          => $address . ', Mukono District',
                'occupation'       => $occupation,
                'nid_number'       => 'CM' . rand(70, 99) . rand(100000, 999999) . 'XY',
                'nok_name'         => $this->randomNokName(),
                'nok_relationship' => 'spouse',
                'nok_phone'        => '+2567' . rand(10000000, 99999999),
                'nok_address'      => $address . ', Mukono District',
                'nok_nid_number'   => 'CM' . rand(70, 99) . rand(100000, 999999) . 'ZW',
                'goats_requested'  => $goatsRequested,
                'notes'            => 'Looking forward to joining the Goat Banking program.',
                'status'           => 'pending',
                'created_at'       => $createdAt,
                'updated_at'       => $createdAt,
            ]);
        }

        echo "✓ 2 pending applications seeded (awaiting review)\n";

        // ── 1 rejected application ──────────────────────────────────────────
        $createdAt = date('Y-m-d H:i:s', strtotime('-18 days'));
        $this->db->table('users')->insert([
            'email'         => 'florence.birungi@example.com',
            'password_hash' => password_hash('Member@2026!', PASSWORD_BCRYPT, ['cost' => 12]),
            'role'          => 'member',
            'status'        => 'rejected',
            'first_name'    => 'Florence',
            'last_name'     => 'Birungi',
            'phone'         => '+2567' . rand(10000000, 99999999),
            'created_at'    => $createdAt,
            'updated_at'    => $createdAt,
        ]);
        $florenceId = $this->db->insertID();

        $this->db->table('member_applications')->insert([
            'user_id'          => $florenceId,
            'first_name'       => 'Florence',
            'last_name'        => 'Birungi',
            'dob'              => date('Y-m-d', strtotime('-24 years')),
            'gender'           => 'female',
            'phone'            => '+2567' . rand(10000000, 99999999),
            'address'          => 'Kayunga Road, Mukono District',
            'occupation'       => 'Student',
            'nid_number'       => 'CM85' . rand(100000, 999999) . 'AB',
            'nok_name'         => $this->randomNokName(),
            'nok_relationship' => 'parent',
            'nok_phone'        => '+2567' . rand(10000000, 99999999),
            'nok_address'      => 'Kayunga Road, Mukono District',
            'nok_nid_number'   => 'CM80' . rand(100000, 999999) . 'CD',
            'goats_requested'  => '5',
            'notes'            => null,
            'status'           => 'rejected',
            'reviewed_by'      => $adminId,
            'reviewed_at'      => date('Y-m-d H:i:s', strtotime($createdAt . ' +3 days')),
            'rejection_reason' => 'Unable to verify next-of-kin contact details. Please reapply with updated information.',
            'created_at'       => $createdAt,
            'updated_at'       => $createdAt,
        ]);

        echo "✓ 1 rejected application seeded\n";

        // ── 1 info_requested application ────────────────────────────────────
        $createdAt = date('Y-m-d H:i:s', strtotime('-6 days'));
        $this->db->table('users')->insert([
            'email'         => 'james.ouma@example.com',
            'password_hash' => password_hash('Member@2026!', PASSWORD_BCRYPT, ['cost' => 12]),
            'role'          => 'member',
            'status'        => 'pending',
            'first_name'    => 'James',
            'last_name'     => 'Ouma',
            'phone'         => '+2567' . rand(10000000, 99999999),
            'created_at'    => $createdAt,
            'updated_at'    => $createdAt,
        ]);
        $jamesId = $this->db->insertID();

        $this->db->table('member_applications')->insert([
            'user_id'           => $jamesId,
            'first_name'        => 'James',
            'last_name'         => 'Ouma',
            'dob'               => date('Y-m-d', strtotime('-33 years')),
            'gender'            => 'male',
            'phone'             => '+2567' . rand(10000000, 99999999),
            'address'           => 'Goma, Mukono District',
            'occupation'        => 'Mechanic',
            'nid_number'        => 'CM77' . rand(100000, 999999) . 'EF',
            'nok_name'          => $this->randomNokName(),
            'nok_relationship'  => 'sibling',
            'nok_phone'         => '+2567' . rand(10000000, 99999999),
            'nok_address'       => 'Goma, Mukono District',
            'nok_nid_number'    => 'CM78' . rand(100000, 999999) . 'GH',
            'goats_requested'   => '2',
            'notes'             => null,
            'status'            => 'info_requested',
            'reviewed_by'       => $adminId,
            'reviewed_at'       => date('Y-m-d H:i:s', strtotime($createdAt . ' +1 day')),
            'info_request_note' => 'Could you confirm your next-of-kin\'s phone number? The one provided did not connect when we called to verify.',
            'created_at'        => $createdAt,
            'updated_at'        => $createdAt,
        ]);

        echo "✓ 1 info-requested application seeded\n";
    }

    private function randomNokName(): string
    {
        $first = ['Margaret', 'Samuel', 'Agnes', 'Vincent', 'Christine', 'Henry', 'Brenda', 'Charles'];
        $last  = ['Nakimuli', 'Lubega', 'Atim', 'Mutebi', 'Akello', 'Ssali'];

        return $first[array_rand($first)] . ' ' . $last[array_rand($last)];
    }
}

// ══════════════════════════════════════════════════════════════════════════════
// GOATS — farm stock + Goat Banking holdings, assigned to approved members
// ══════════════════════════════════════════════════════════════════════════════

class GoatSeeder extends Seeder
{
    public function run(): void
    {
        $now   = date('Y-m-d H:i:s');
        $goats = [];

        // 10 original sample goats (farm stock, unassigned)
        $names  = ['Kito', 'Nia', 'Duma', 'Amara', 'Zuri', 'Paka', 'Tuli', 'Bora', 'Tamu', 'Kopa'];
        $breeds = ['Boer', 'Boer Cross', 'Galla', 'Galla Cross', 'Local Cross'];
        $sexes  = ['male', 'female'];

        foreach ($names as $i => $name) {
            $goats[] = [
                'tag_number' => 'PGF-' . str_pad((string) (1180 + $i), 4, '0', STR_PAD_LEFT),
                'name'       => $name,
                'breed'      => $breeds[$i % count($breeds)],
                'sex'        => $sexes[$i % 2],
                'dob'        => date('Y-m-d', strtotime('-' . rand(4, 24) . ' months')),
                'status'     => 'active',
                'pen_id'     => 'Pen ' . (($i % 4) + 1),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // 8 more farm stock goats
        $moreNames = ['Simba', 'Rafiki', 'Jasiri', 'Nuru', 'Hodari', 'Imara', 'Sahara', 'Tembo'];
        foreach ($moreNames as $i => $name) {
            $goats[] = [
                'tag_number' => 'PGF-' . (1190 + $i),
                'name'       => $name,
                'breed'      => $breeds[$i % count($breeds)],
                'sex'        => $sexes[$i % 2],
                'dob'        => date('Y-m-d', strtotime('-' . rand(3, 30) . ' months')),
                'status'     => 'active',
                'pen_id'     => 'Pen ' . (($i % 4) + 1),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $this->db->table('goats')->insertBatch($goats);
        echo "✓ " . count($goats) . " farm stock goats seeded\n";

        // ── Goat Banking holdings — assign goats to approved members ─────────
        $memberGoatNames = ['Asante', 'Furaha', 'Bahati', 'Upendo', 'Heri', 'Amani', 'Dhahabu', 'Faraja', 'Subira', 'Tumaini', 'Baraka', 'Neema', 'Zawadi', 'Imani'];

        $members = $this->db->table('users')
            ->where('role', 'member')
            ->where('status', 'active')
            ->orderBy('id', 'ASC')
            ->get()->getResultArray();

        $tagCounter   = 1200;
        $nameIndex    = 0;
        $assignedGoats = [];

        foreach ($members as $member) {
            // Pull goats_requested from their application
            $app = $this->db->table('member_applications')->where('user_id', $member['id'])->get()->getRowArray();
            $goatsRequested = $app ? max(1, min(3, (int) $app['goats_requested'])) : 1;

            for ($j = 0; $j < $goatsRequested && $nameIndex < count($memberGoatNames); $j++) {
                $tag = 'PGF-' . $tagCounter++;
                $assignedGoats[] = [
                    'tag_number' => $tag,
                    'name'       => $memberGoatNames[$nameIndex++],
                    'breed'      => $breeds[array_rand($breeds)],
                    'sex'        => $sexes[array_rand($sexes)],
                    'dob'        => date('Y-m-d', strtotime('-' . rand(6, 20) . ' months')),
                    'member_id'  => $member['id'],
                    'status'     => 'active',
                    'pen_id'     => 'Pen ' . rand(1, 4),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (! empty($assignedGoats)) {
            $this->db->table('goats')->insertBatch($assignedGoats);
        }

        echo "✓ " . count($assignedGoats) . " goats allocated to Goat Banking members\n";
    }
}

// ══════════════════════════════════════════════════════════════════════════════
// WEIGHT LOGS — a few months of readings so growth charts have data
// ══════════════════════════════════════════════════════════════════════════════

class WeightLogSeeder extends Seeder
{
    public function run(): void
    {
        $goats = $this->db->table('goats')->orderBy('id', 'ASC')->limit(20)->get()->getResultArray();

        $logs = [];
        foreach ($goats as $goat) {
            $startWeight = rand(8, 14); // young goat starting weight in kg
            for ($monthsAgo = 5; $monthsAgo >= 0; $monthsAgo--) {
                $weight = $startWeight + ((5 - $monthsAgo) * rand(2, 4)) + (rand(-5, 5) / 10);
                $logs[] = [
                    'goat_id'   => $goat['id'],
                    'weight_kg' => round($weight, 1),
                    'logged_by' => null,
                    'logged_at' => date('Y-m-d H:i:s', strtotime("-{$monthsAgo} months")),
                    'created_at' => date('Y-m-d H:i:s', strtotime("-{$monthsAgo} months")),
                ];
            }
        }

        if (! empty($logs)) {
            $this->db->table('weight_logs')->insertBatch($logs);
        }

        echo "✓ " . count($logs) . " weight log entries seeded across " . count($goats) . " animals\n";
    }
}

// ══════════════════════════════════════════════════════════════════════════════
// VET VISITS — checkups, vaccinations, and a few active health flags
// ══════════════════════════════════════════════════════════════════════════════

class VetVisitSeeder extends Seeder
{
    public function run(): void
    {
        $vets  = $this->db->table('users')->where('role', 'vet')->get()->getResultArray();
        $goats = $this->db->table('goats')->orderBy('id', 'ASC')->limit(20)->get()->getResultArray();

        if (empty($vets) || empty($goats)) {
            return;
        }

        $types = ['routine_checkup', 'vaccination', 'weight_check', 'deworming', 'treatment', 'follow_up'];
        $visits = [];

        foreach ($goats as $i => $goat) {
            $visitCount = rand(1, 3);
            for ($v = 0; $v < $visitCount; $v++) {
                $isFlagged = ($i % 7 === 0 && $v === 0); // sprinkle in a few flagged visits
                $isResolved = $isFlagged && ($i % 14 === 0); // half of those already resolved

                $visitDate = date('Y-m-d H:i:s', strtotime('-' . rand(1, 150) . ' days'));

                $visits[] = [
                    'goat_id'          => $goat['id'],
                    'vet_id'           => $vets[array_rand($vets)]['id'],
                    'visit_type'       => $isFlagged ? 'treatment' : $types[array_rand($types)],
                    'visit_date'       => $visitDate,
                    'temperature'      => $isFlagged ? round(rand(390, 405) / 10, 1) : round(rand(385, 392) / 10, 1),
                    'weight_kg'        => null,
                    'medication'       => $isFlagged ? 'Oxytetracycline 5ml IM, repeat in 48hrs' : null,
                    'clinical_notes'   => $isFlagged
                        ? 'Animal presented with mild fever and reduced appetite. Started on antibiotics, monitoring closely.'
                        : 'Routine examination — animal appears healthy, no concerns noted.',
                    'is_flagged'       => $isFlagged ? 1 : 0,
                    'flag_reason'      => $isFlagged ? 'Fever and reduced appetite — under treatment and observation' : null,
                    'followup_date'    => $isFlagged && ! $isResolved ? date('Y-m-d', strtotime('+5 days')) : null,
                    'flag_resolved_at' => $isResolved ? date('Y-m-d H:i:s', strtotime($visitDate . ' +6 days')) : null,
                    'created_at'       => $visitDate,
                    'updated_at'       => $visitDate,
                ];
            }
        }

        if (! empty($visits)) {
            $this->db->table('vet_visits')->insertBatch($visits);
        }

        $flaggedCount = count(array_filter($visits, fn($v) => $v['is_flagged'] === 1));
        echo "✓ " . count($visits) . " vet visits seeded ({$flaggedCount} flagged)\n";
    }
}

// ══════════════════════════════════════════════════════════════════════════════
// VET SCHEDULE — today's and this week's tasks
// ══════════════════════════════════════════════════════════════════════════════

class VetScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $vets       = $this->db->table('users')->where('role', 'vet')->get()->getResultArray();
        $managerId  = $this->db->table('users')->where('email', 'manager@mdgoatco.farm')->get()->getRow()->id ?? null;

        if (empty($vets)) {
            return;
        }

        $tasks = [
            ['Morning health check — Pen 1',       'routine_checkup', 'today',   'Pen 1 animals'],
            ['Vaccination round — Pen 2',           'vaccination',      'today',   'Pen 2 animals'],
            ['Deworming — new arrivals',            'deworming',        '+1 day',  'Recently tagged goats'],
            ['Follow-up check — flagged animals',   'follow_up',        '+2 days', 'Flagged animals only'],
            ['Weight check — Goat Banking herd',    'weight_check',     '+4 days', 'Member-assigned goats'],
            ['Routine checkup — Pen 3 & 4',         'routine_checkup',  '+6 days', 'Pen 3 and 4 animals'],
        ];

        $rows = [];
        foreach ($tasks as $i => [$task, $type, $when, $animalsDesc]) {
            $scheduledAt = $when === 'today'
                ? date('Y-m-d', strtotime('today')) . ' ' . sprintf('%02d:00:00', 8 + $i)
                : date('Y-m-d H:i:s', strtotime($when));

            $rows[] = [
                'task'            => $task,
                'description'     => ucfirst(str_replace('_', ' ', $type)) . ' as part of routine herd health management.',
                'assigned_vet_id' => $vets[$i % count($vets)]['id'],
                'animals_desc'    => $animalsDesc,
                'scheduled_at'    => $scheduledAt,
                'status'          => 'scheduled',
                'created_by'      => $managerId,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ];
        }

        $this->db->table('vet_schedules')->insertBatch($rows);
        echo "✓ " . count($rows) . " vet schedule tasks seeded\n";
    }
}

// ══════════════════════════════════════════════════════════════════════════════
// TRANSACTIONS — wallet ledger for approved members
// ══════════════════════════════════════════════════════════════════════════════

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $members = $this->db->table('users')
            ->where('role', 'member')
            ->where('status', 'active')
            ->get()->getResultArray();

        $adminId = $this->db->table('users')->where('email', 'admin@mdgoatco.farm')->get()->getRow()->id;

        $count = 0;
        foreach ($members as $member) {
            $balance = 0;

            // Initial Goat Banking deposit
            $deposit    = [2000000, 3000000, 4500000, 6000000][array_rand([2000000, 3000000, 4500000, 6000000])];
            $balance   += $deposit;
            $createdAt  = date('Y-m-d H:i:s', strtotime('-' . rand(40, 200) . ' days'));

            $this->db->table('transactions')->insert([
                'member_id'     => $member['id'],
                'type'          => 'credit',
                'amount'        => $deposit,
                'description'   => 'Initial Goat Banking deposit',
                'reference'     => 'TXN-' . strtoupper(bin2hex(random_bytes(4))),
                'balance_after' => $balance,
                'created_by'    => $adminId,
                'created_at'    => $createdAt,
            ]);
            $count++;

            // ~60% chance of a second top-up later
            if (rand(1, 10) <= 6) {
                $topup      = [200000, 350000, 500000, 750000][array_rand([200000, 350000, 500000, 750000])];
                $balance   += $topup;
                $topupDate  = date('Y-m-d H:i:s', strtotime($createdAt . ' +' . rand(15, 60) . ' days'));

                $this->db->table('transactions')->insert([
                    'member_id'     => $member['id'],
                    'type'          => 'credit',
                    'amount'        => $topup,
                    'description'   => 'Wallet top-up',
                    'reference'     => 'TXN-' . strtoupper(bin2hex(random_bytes(4))),
                    'balance_after' => $balance,
                    'created_by'    => null,
                    'created_at'    => $topupDate,
                ]);
                $count++;
            }

            // ~25% chance of a small debit (e.g. a service fee)
            if (rand(1, 10) <= 3 && $balance > 100000) {
                $debit      = [25000, 50000][array_rand([25000, 50000])];
                $balance   -= $debit;
                $debitDate  = date('Y-m-d H:i:s', strtotime($createdAt . ' +' . rand(61, 90) . ' days'));

                $this->db->table('transactions')->insert([
                    'member_id'     => $member['id'],
                    'type'          => 'debit',
                    'amount'        => $debit,
                    'description'   => 'Annual herd management fee',
                    'reference'     => 'TXN-' . strtoupper(bin2hex(random_bytes(4))),
                    'balance_after' => $balance,
                    'created_by'    => $adminId,
                    'created_at'    => $debitDate,
                ]);
                $count++;
            }
        }

        echo "✓ {$count} wallet transactions seeded across " . count($members) . " members\n";
    }
}

// ══════════════════════════════════════════════════════════════════════════════
// PAYMENTS — sample Pesapal wallet top-up attempts in various states
// ══════════════════════════════════════════════════════════════════════════════

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $members = $this->db->table('users')
            ->where('role', 'member')
            ->where('status', 'active')
            ->limit(4)
            ->get()->getResultArray();

        if (empty($members)) {
            return;
        }

        $rows = [];

        // A completed top-up, linked to a real transaction so the link isn't dangling.
        $firstMemberTxn = $this->db->table('transactions')
            ->where('member_id', $members[0]['id'])
            ->orderBy('created_at', 'DESC')
            ->get()->getRowArray();

        $rows[] = [
            'member_id'           => $members[0]['id'],
            'merchant_reference'  => 'GBANK-' . strtoupper(bin2hex(random_bytes(5))),
            'order_tracking_id'   => bin2hex(random_bytes(16)),
            'amount'              => $firstMemberTxn['amount'] ?? 200000,
            'currency'            => 'UGX',
            'description'         => 'Goat Banking wallet top-up',
            'status'              => 'completed',
            'payment_method'      => 'Mobile Money',
            'confirmation_code'   => strtoupper(bin2hex(random_bytes(4))),
            'raw_response'        => null,
            'transaction_id'      => $firstMemberTxn['id'] ?? null,
            'created_at'          => date('Y-m-d H:i:s', strtotime('-10 days')),
            'updated_at'          => date('Y-m-d H:i:s', strtotime('-10 days')),
        ];

        // A pending top-up — awaiting Pesapal confirmation.
        $rows[] = [
            'member_id'          => $members[1]['id'] ?? $members[0]['id'],
            'merchant_reference' => 'GBANK-' . strtoupper(bin2hex(random_bytes(5))),
            'order_tracking_id'  => bin2hex(random_bytes(16)),
            'amount'             => 150000,
            'currency'           => 'UGX',
            'description'        => 'Goat Banking wallet top-up',
            'status'             => 'pending',
            'created_at'         => date('Y-m-d H:i:s', strtotime('-2 hours')),
            'updated_at'         => date('Y-m-d H:i:s', strtotime('-2 hours')),
        ];

        // A failed attempt.
        $rows[] = [
            'member_id'          => $members[2]['id'] ?? $members[0]['id'],
            'merchant_reference' => 'GBANK-' . strtoupper(bin2hex(random_bytes(5))),
            'order_tracking_id'  => bin2hex(random_bytes(16)),
            'amount'             => 100000,
            'currency'           => 'UGX',
            'description'        => 'Goat Banking wallet top-up',
            'status'             => 'failed',
            'created_at'         => date('Y-m-d H:i:s', strtotime('-3 days')),
            'updated_at'         => date('Y-m-d H:i:s', strtotime('-3 days')),
        ];

        // A second completed top-up for variety in the admin payments list.
        if (isset($members[3])) {
            $rows[] = [
                'member_id'           => $members[3]['id'],
                'merchant_reference'  => 'GBANK-' . strtoupper(bin2hex(random_bytes(5))),
                'order_tracking_id'   => bin2hex(random_bytes(16)),
                'amount'              => 300000,
                'currency'            => 'UGX',
                'description'         => 'Goat Banking wallet top-up',
                'status'              => 'completed',
                'payment_method'      => 'Visa',
                'confirmation_code'   => strtoupper(bin2hex(random_bytes(4))),
                'created_at'          => date('Y-m-d H:i:s', strtotime('-25 days')),
                'updated_at'          => date('Y-m-d H:i:s', strtotime('-25 days')),
            ];
        }

        $this->db->table('payments')->insertBatch($rows);
        echo "✓ " . count($rows) . " sample Pesapal payment records seeded\n";
    }
}

// ══════════════════════════════════════════════════════════════════════════════
// NOTIFICATIONS — a handful of in-app notifications for demo richness
// ══════════════════════════════════════════════════════════════════════════════

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $admins  = $this->db->table('users')->where('role', 'super_admin')->get()->getResultArray();
        $members = $this->db->table('users')->where('role', 'member')->where('status', 'active')->limit(5)->get()->getResultArray();

        $rows = [];

        foreach ($admins as $admin) {
            $rows[] = [
                'user_id'    => $admin['id'],
                'title'      => 'New Goat Banking application',
                'body'       => 'Patricia Achieng has submitted an application.',
                'type'       => 'info',
                'link'       => '/admin/applications',
                'is_read'    => 0,
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ];
            $rows[] = [
                'user_id'    => $admin['id'],
                'title'      => 'Health flag raised: Kito (PGF-1180)',
                'body'       => 'A vet has flagged a health concern that needs review.',
                'type'       => 'alert',
                'link'       => '/admin/herd',
                'is_read'    => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-12 days')),
            ];
        }

        foreach ($members as $member) {
            $rows[] = [
                'user_id'    => $member['id'],
                'title'      => 'Your Goat Banking application has been approved! 🎉',
                'body'       => 'Welcome to MD Goatco Farm Goat Banking. Log in to view your dashboard.',
                'type'       => 'success',
                'link'       => '/member/dashboard',
                'is_read'    => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-100 days')),
            ];
        }

        if (! empty($rows)) {
            $this->db->table('notifications')->insertBatch($rows);
        }

        echo "✓ " . count($rows) . " notifications seeded\n";
    }
}
