<?php

namespace App\Console\Commands;

use App\Domain\Alerting\Actions\CreateAlertAction;
use App\Domain\Device\Contracts\DeviceRepositoryInterface;
use Illuminate\Console\Command;

class CheckOfflineSensors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-offline-sensors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check offline sensors';

    public function __construct(
        private readonly DeviceRepositoryInterface $deviceRepository,
        private readonly CreateAlertAction $createAlertAction
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $devices = $this->deviceRepository->getActiveDevices();

        foreach ($devices as $device) {
            $this->createAlertAction->executeForDeviceOffline($device);
        }
    }
}
