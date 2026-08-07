<?php
declare(strict_types=1);
namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;

/**
 * Pure-logic tests for app/Helpers/goatco_helper.php — these functions run
 * on nearly every page (goat ages, status badges, role labels...), so a
 * silent regression here would misrender data everywhere without ever
 * throwing an error. No database needed.
 */
final class GoatcoHelperTest extends CIUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        helper('goatco');
    }

    public function testGoatAgeWithNullDob(): void
    {
        $this->assertSame('—', goatAge(null));
    }

    public function testGoatAgeInYears(): void
    {
        $dob = date('Y-m-d', strtotime('-3 years -2 months'));
        $this->assertSame('3 yrs', goatAge($dob));
    }

    public function testGoatAgeSingularYear(): void
    {
        $dob = date('Y-m-d', strtotime('-1 year -1 month'));
        $this->assertSame('1 yr', goatAge($dob));
    }

    public function testGoatAgeInMonthsWhenUnderOneYear(): void
    {
        $dob = date('Y-m-d', strtotime('-5 months'));
        $this->assertSame('5 mo', goatAge($dob));
    }

    public function testGoatAgeInDaysWhenUnderOneMonth(): void
    {
        $dob = date('Y-m-d', strtotime('-3 days'));
        $this->assertSame('3 days', goatAge($dob));
    }

    public function testGoatAgeSingularDay(): void
    {
        $dob = date('Y-m-d', strtotime('-1 day'));
        $this->assertSame('1 day', goatAge($dob));
    }

    public function testFormatUgx(): void
    {
        $this->assertSame('UGX 3,500,000', formatUgx(3500000));
        $this->assertSame('UGX 0', formatUgx(0));
    }

    public function testStatusBadgeKnownStatuses(): void
    {
        $this->assertSame('<span class="badge badge-active">Active</span>', statusBadge('active'));
        $this->assertSame('<span class="badge badge-pending">Pending</span>', statusBadge('pending'));
        $this->assertSame('<span class="badge badge-rejected">Rejected</span>', statusBadge('rejected'));
        $this->assertSame('<span class="badge badge-rejected">Inactive</span>', statusBadge('inactive'));
    }

    public function testStatusBadgeUnknownStatusFallsBackToPending(): void
    {
        $this->assertSame('<span class="badge badge-pending">Weird</span>', statusBadge('weird'));
    }

    public function testStatusBadgeEscapesOutput(): void
    {
        // A malicious/garbage status must never make it into the HTML unescaped.
        $badge = statusBadge('<script>alert(1)</script>');
        $this->assertStringNotContainsString('<script>', $badge);
    }

    public function testRoleLabel(): void
    {
        $this->assertSame('Super Administrator', roleLabel('super_admin'));
        $this->assertSame('Farm Manager', roleLabel('manager'));
        $this->assertSame('Veterinarian', roleLabel('vet'));
        $this->assertSame('Goat Banking Member', roleLabel('member'));
        $this->assertSame('Guest', roleLabel('guest'));
    }

    public function testInitials(): void
    {
        $this->assertSame('EN', initials('Esther', 'Nakato'));
        $this->assertSame('JD', initials('john', 'doe'));
    }

    public function testVisitTypeLabel(): void
    {
        $this->assertSame('Routine Check', visitTypeLabel('routine'));
        $this->assertSame('Vaccination', visitTypeLabel('vaccination'));
        $this->assertSame('Weight Check', visitTypeLabel('weight_check'));
        $this->assertSame('Custom type', visitTypeLabel('custom_type'));
    }

    public function testTimeAgoJustNow(): void
    {
        $this->assertSame('just now', time_ago(date('Y-m-d H:i:s')));
    }

    public function testTimeAgoMinutesAgo(): void
    {
        $this->assertSame('5 min ago', time_ago(date('Y-m-d H:i:s', strtotime('-5 minutes'))));
    }

    public function testTimeAgoHoursAgo(): void
    {
        $this->assertSame('2 hr ago', time_ago(date('Y-m-d H:i:s', strtotime('-2 hours'))));
    }

    public function testTimeAgoDaysAgo(): void
    {
        $this->assertSame('3 days ago', time_ago(date('Y-m-d H:i:s', strtotime('-3 days'))));
    }

    public function testTimeAgoOlderThanAWeekFallsBackToDate(): void
    {
        $tenDaysAgo = strtotime('-10 days');
        $this->assertSame(date('j M Y', $tenDaysAgo), time_ago(date('Y-m-d H:i:s', $tenDaysAgo)));
    }
}
