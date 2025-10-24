<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Device\Actions;

use App\Domain\Device\Actions\CreateDeviceAction;
use App\Domain\Device\Contracts\DeviceRepositoryInterface;
use App\Domain\Device\Models\Device;
use App\Domain\User\Models\User;
use Mockery;
use Tests\TestCase;

/**
 * Unit tests for CreateDeviceAction.
 *
 * Tests the business logic for device creation and ownership assignment.
 */
class CreateDeviceActionTest extends TestCase
{
    private DeviceRepositoryInterface $deviceRepository;
    private CreateDeviceAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->deviceRepository = Mockery::mock(DeviceRepositoryInterface::class);
        $this->action = new CreateDeviceAction($this->deviceRepository);
    }

    public function test_execute_creates_device_with_user_assignment(): void
    {
        $user = new User(['name' => 'John Doe', 'email' => 'john@example.com']);
        $user->id = 1;

        $deviceData = [
            'serial_number' => 'DEV001',
            'name' => 'Temperature Sensor 1',
        ];

        $expectedDevice = new Device($deviceData);
        $expectedDevice->id = 1;
        $expectedDevice->user_id = 1;
        $expectedDevice->is_active = true;

        $this->deviceRepository
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($data) {
                return $data['serial_number'] === 'DEV001'
                    && $data['name'] === 'Temperature Sensor 1'
                    && $data['user_id'] === 1
                    && $data['is_active'] === true;
            }))
            ->andReturn($expectedDevice);

        $result = $this->action->execute($user, $deviceData);

        $this->assertInstanceOf(Device::class, $result);
        $this->assertEquals('DEV001', $result->serial_number);
        $this->assertEquals('Temperature Sensor 1', $result->name);
        $this->assertEquals(1, $result->user_id);
        $this->assertTrue($result->is_active);
    }

    public function test_execute_sets_default_is_active_to_true(): void
    {
        $user = new User(['name' => 'John Doe', 'email' => 'john@example.com']);
        $user->id = 1;

        $deviceData = [
            'serial_number' => 'DEV001',
            'name' => 'Temperature Sensor 1',
        ];

        $expectedDevice = new Device($deviceData);
        $expectedDevice->id = 1;

        $this->deviceRepository
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($data) {
                return $data['is_active'] === true;
            }))
            ->andReturn($expectedDevice);

        $result = $this->action->execute($user, $deviceData);

        $this->assertInstanceOf(Device::class, $result);
    }
}
