<?php

declare(strict_types=1);

namespace App\Domain\User\Actions;

use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Domain\User\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Action for creating a new user.
 *
 * This action encapsulates the business logic for user creation,
 * including password hashing and validation.
 */
class CreateUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /**
     * Execute the user creation action.
     *
     * @param array $data User data including name, email, and password
     * @return User The created user
     */
    public function execute(array $data): User
    {
        // Hash the password before storing
        $data['password'] = Hash::make($data['password']);

        return $this->userRepository->create($data);
    }
}
