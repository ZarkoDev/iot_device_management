<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\User\Actions;

use App\Domain\User\Actions\CreateUserAction;
use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

/**
 * Unit tests for CreateUserAction.
 *
 * Tests the business logic for user creation including password hashing.
 */
class CreateUserActionTest extends TestCase
{
    use RefreshDatabase;

    private UserRepositoryInterface $userRepository;
    private CreateUserAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
        $this->action = new CreateUserAction($this->userRepository);
    }

    public function test_execute_creates_user_with_hashed_password(): void
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'pass123',
        ];

        $expectedUser = new User($userData);
        $expectedUser->id = 1;

        $this->userRepository
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($data) {
                return $data['name'] === 'John Doe'
                    && $data['email'] === 'john@example.com'
                    && Hash::check('pass123', $data['password']);
            }))
            ->andReturn($expectedUser);

        $result = $this->action->execute($userData);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('John Doe', $result->name);
        $this->assertEquals('john@example.com', $result->email);
    }
}
