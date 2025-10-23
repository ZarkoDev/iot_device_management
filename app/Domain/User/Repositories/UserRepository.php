<?php

declare(strict_types=1);

namespace App\Domain\User\Repositories;

use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Domain\User\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Eloquent implementation of UserRepositoryInterface.
 *
 *  This repository handles all user data access operations using Eloquent ORM.
 *  Implements the Repository pattern for clean separation of data access logic.
 */
class UserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh();
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return User::paginate($perPage);
    }
}
