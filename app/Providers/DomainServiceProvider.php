<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Alerting\Contracts\AlertRepositoryInterface;
use App\Domain\Alerting\Factories\AlertFactory;
use App\Domain\Alerting\Repositories\AlertRepository;
use App\Domain\Alerting\Services\AlertService;
use App\Domain\Device\Contracts\DeviceRepositoryInterface;
use App\Domain\Device\Repositories\DeviceRepository;
use App\Domain\SensorData\Contracts\SensorDataRepositoryInterface;
use App\Domain\SensorData\Repositories\SensorDataRepository;
use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Domain\User\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Service Provider for Domain layer dependency injection.
 *
 * This provider binds repository interfaces to their implementations,
 * following the Dependency Inversion Principle.
 */
class DomainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(DeviceRepositoryInterface::class, DeviceRepository::class);
        $this->app->bind(SensorDataRepositoryInterface::class, SensorDataRepository::class);
        $this->app->bind(AlertRepositoryInterface::class, AlertRepository::class);

        // Bind alert services
        $this->app->singleton(AlertFactory::class);
        $this->app->singleton(AlertService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
