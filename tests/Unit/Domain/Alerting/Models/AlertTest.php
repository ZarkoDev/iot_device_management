<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Alerting\Models;

use App\Domain\Alerting\Models\Alert;
use Tests\TestCase;

/**
 * Unit tests for Alert model.
 *
 * Tests the business logic methods of the Alert model.
 */
class AlertTest extends TestCase
{
    public function test_is_active_returns_true_for_unresolved_alerts(): void
    {
        $alert = new Alert(['resolved_at' => null]);

        $this->assertTrue($alert->isActive());
    }

    public function test_is_active_returns_false_for_resolved_alerts(): void
    {
        $alert = new Alert(['resolved_at' => now()]);

        $this->assertFalse($alert->isActive());
    }

    public function test_resolve_sets_alert_as_resolved(): void
    {
        $alert = new Alert(['resolved_at' => null]);
        $alert->exists = true;

        $alert->resolve();

        $this->assertNotNull($alert->resolved_at);
    }
}
