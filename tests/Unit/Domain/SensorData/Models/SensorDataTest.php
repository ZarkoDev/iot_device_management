<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\SensorData\Models;

use App\Domain\SensorData\Models\SensorData;
use Tests\TestCase;

/**
 * Unit tests for SensorData model.
 *
 * Tests the business logic methods of the SensorData model.
 */
class SensorDataTest extends TestCase
{
    public function test_is_within_normal_range_returns_true_for_normal_temperatures(): void
    {
        $sensorData = new SensorData(['temperature' => 20.5]);

        $this->assertTrue($sensorData->isWithinNormalRange());
    }

    public function test_is_within_normal_range_returns_false_for_temperatures_below_zero(): void
    {
        $sensorData = new SensorData(['temperature' => -5.0]);

        $this->assertFalse($sensorData->isWithinNormalRange());
    }

    public function test_is_within_normal_range_returns_false_for_temperatures_above_thirty(): void
    {
        $sensorData = new SensorData(['temperature' => 35.0]);

        $this->assertFalse($sensorData->isWithinNormalRange());
    }

    public function test_should_trigger_alert_returns_true_for_abnormal_temperatures(): void
    {
        $sensorData = new SensorData(['temperature' => -10.0]);

        $this->assertTrue($sensorData->shouldTriggerAlert());
    }

    public function test_should_trigger_alert_returns_false_for_normal_temperatures(): void
    {
        $sensorData = new SensorData(['temperature' => 25.0]);

        $this->assertFalse($sensorData->shouldTriggerAlert());
    }

    public function test_formatted_temperature_attribute_returns_correctly_formatted_string(): void
    {
        $sensorData = new SensorData(['temperature' => 23.456]);

        $this->assertEquals('23.46°C', $sensorData->formatted_temperature);
    }
}
