<?php

declare(strict_types=1);

namespace App\Providers;

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
        // Bind repository interfaces to implementations
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
