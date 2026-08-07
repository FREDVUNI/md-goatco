<?php
declare(strict_types=1);
namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;

/**
 * Exercises the same CI4 Throttler service BaseController::tooManyAttempts()
 * wraps, for login / forgot-password / contact-form rate limiting. Confirms
 * the actual security property: N requests succeed, the next one is blocked.
 */
final class ThrottleTest extends CIUnitTestCase
{
    public function testAllowsUpToCapacityThenBlocks(): void
    {
        $throttler = \Config\Services::throttler(false);
        $key       = 'test_throttle_' . bin2hex(random_bytes(4)); // unique per run, avoids cross-test bleed

        for ($i = 1; $i <= 5; $i++) {
            $this->assertTrue($throttler->check($key, 5, 60), "attempt $i should be allowed");
        }
        $this->assertFalse($throttler->check($key, 5, 60), 'the 6th attempt within the window should be blocked');
    }

    public function testDifferentKeysAreThrottledIndependently(): void
    {
        $throttler = \Config\Services::throttler(false);
        $keyA      = 'test_throttle_a_' . bin2hex(random_bytes(4));
        $keyB      = 'test_throttle_b_' . bin2hex(random_bytes(4));

        for ($i = 1; $i <= 3; $i++) {
            $throttler->check($keyA, 3, 60);
        }
        $this->assertFalse($throttler->check($keyA, 3, 60), 'key A should now be exhausted');
        $this->assertTrue($throttler->check($keyB, 3, 60), 'key B is a different bucket and should still be fresh');
    }
}
