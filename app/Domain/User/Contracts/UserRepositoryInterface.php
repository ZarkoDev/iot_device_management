<?php

declare(strict_types=1);

namespace App\Domain\User\Contracts;

use App\Domain\User\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Repository contract for User domain operations.
 *
 * This interface defines the contract for user data access operations,
 * ensuring data isolation and providing a clean abstraction layer.
 */
interface UserRepositoryInterface
{
    /**
     * Find User by id
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User;

    /**
     * Find User by email
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * Create User
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User;

    /**
     * Update User
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    public function update(User $user, array $data): User;

    /**
     * Delete User
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool;

    /**
     * Return users with pagination
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;
}
