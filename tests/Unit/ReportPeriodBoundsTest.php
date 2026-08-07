<?php
declare(strict_types=1);
namespace Tests\Unit;

use App\Modules\Admin\Controllers\ReportController;
use CodeIgniter\Test\CIUnitTestCase;
use ReflectionMethod;

/**
 * Admin\ReportController::periodBounds() computes the date range for the
 * Reports & Audit page (week/month/year, with prev/next navigation via
 * $offset) and directly drives which activity rows and stats get shown —
 * an off-by-one here silently shows the wrong period's data. It's private,
 * so this calls it via reflection rather than changing its visibility just
 * for testing.
 */
final class ReportPeriodBoundsTest extends CIUnitTestCase
{
    /** @return array{0: \DateTimeImmutable, 1: \DateTimeImmutable, 2: string} */
    private function call(string $range, int $offset): array
    {
        $method = new ReflectionMethod(ReportController::class, 'periodBounds');
        $method->setAccessible(true);
        return $method->invoke(new ReportController(), $range, $offset);
    }

    public function testMonthCurrentPeriodSpansFirstToLastDay(): void
    {
        [$start, $end, $label] = $this->call('month', 0);
        $this->assertSame('01', $start->format('d'));
        $this->assertSame('00:00:00', $start->format('H:i:s'));
        // end is exclusive — exactly one calendar month after start.
        $this->assertSame($start->modify('+1 month')->format('Y-m-d'), $end->format('Y-m-d'));
        $this->assertSame($start->format('F Y'), $label);
    }

    public function testMonthOffsetMovesByWholeCalendarMonths(): void
    {
        [$thisMonthStart] = $this->call('month', 0);
        [$prevMonthStart] = $this->call('month', -1);
        [$nextMonthStart] = $this->call('month', 1);

        $this->assertSame($thisMonthStart->modify('-1 month')->format('Y-m-d'), $prevMonthStart->format('Y-m-d'));
        $this->assertSame($thisMonthStart->modify('+1 month')->format('Y-m-d'), $nextMonthStart->format('Y-m-d'));
    }

    public function testWeekStartsOnMondayAndSpansSevenDays(): void
    {
        [$start, $end] = $this->call('week', 0);
        $this->assertSame('Monday', $start->format('l'));
        $this->assertSame('00:00:00', $start->format('H:i:s'));
        $this->assertSame(7, (int) $start->diff($end)->format('%a'));
    }

    public function testWeekOffsetMovesByWholeWeeks(): void
    {
        [$thisWeekStart] = $this->call('week', 0);
        [$prevWeekStart] = $this->call('week', -1);
        $this->assertSame($thisWeekStart->modify('-7 days')->format('Y-m-d'), $prevWeekStart->format('Y-m-d'));
    }

    public function testYearSpansJanuaryFirstToNextJanuaryFirst(): void
    {
        [$start, $end, $label] = $this->call('year', 0);
        $currentYear = (int) date('Y');
        $this->assertSame("$currentYear-01-01 00:00:00", $start->format('Y-m-d H:i:s'));
        $this->assertSame(($currentYear + 1) . '-01-01 00:00:00', $end->format('Y-m-d H:i:s'));
        $this->assertSame((string) $currentYear, $label);
    }

    public function testYearOffsetMovesByWholeYears(): void
    {
        [$start] = $this->call('year', -2);
        $this->assertSame((int) date('Y') - 2, (int) $start->format('Y'));
    }

    public function testEveryRangeTypeProducesAnEndStrictlyAfterStart(): void
    {
        foreach (['week', 'month', 'year'] as $range) {
            [$start, $end] = $this->call($range, 0);
            $this->assertGreaterThan($start, $end, "end must be after start for range=$range");
        }
    }
}
