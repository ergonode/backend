<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Tests\Application\Security\Provider;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Query\UserQueryInterface;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Authentication\Application\Security\Provider\EmailUserProvider;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class EmailUserProviderTest extends TestCase
{
    /**
     * @var UserQueryInterface|MockObject
     */
    private $mockUserQuery;
    /**
     * @var UserRepositoryInterface|MockObject
     */
    private $mockUserRepository;
    private EmailUserProvider $provider;

    protected function setUp(): void
    {
        $this->mockUserQuery = $this->createMock(UserQueryInterface::class);
        $this->mockUserRepository = $this->createMock(UserRepositoryInterface::class);

        $this->provider = new EmailUserProvider(
            $this->mockUserQuery,
            $this->mockUserRepository,
        );
    }

    public function testShouldProvide(): void
    {
        $user = $this->createMock(User::class);
        $user
            ->method('getId')
            ->willReturn($userId = new UserId((string) Uuid::uuid4()));
        $user
            ->method('getPassword')
            ->willReturn('password');
        $user
            ->method('getRoles')
            ->willReturn(['roles']);
        $user
            ->method('isActive')
            ->willReturn(true);
        $this->mockUserQuery
            ->method('findIdByEmail')
            ->willReturn(new UserId((string) Uuid::uuid4()));
        $this->mockUserRepository
            ->method('load')
            ->willReturn($user);

        $result = $this->provider->loadUserByUsername('test@ergonode.com');

        $this->assertSame(
            $user,
            $result,
        );
    }

    public function testThrowExceptionWhenNoSuchUser(): void
    {
        $this->mockUserQuery
            ->method('findIdByEmail')
            ->willReturn(new UserId((string) Uuid::uuid4()));
        $this->mockUserRepository
            ->method('load')
            ->willReturn(null);

        $this->expectExceptionMessage('Invalid credentials');

        $this->provider->loadUserByUsername('test@ergonode.com');
    }

    public function testThrowExceptionWhenNoSuchUserName(): void
    {
        $this->mockUserQuery
            ->method('findIdByEmail')
            ->willReturn(null);
        $this->mockUserRepository
            ->expects($this->never())
            ->method('load');

        $this->expectExceptionMessage('Invalid credentials');

        $this->provider->loadUserByUsername('test@ergonode.com');
    }

    public function testThrowExceptionWhenInvalidEmailGiven(): void
    {
        $this->expectExceptionMessage('Invalid email format');

        $this->provider->loadUserByUsername('plain string');
    }

    public function testThrowExceptionWhenNoStringGiven(): void
    {
        $this->expectExceptionMessage('Username has to be a string');

        $this->provider->loadUserByUsername(1);
    }

    public function testThrowExceptionWhenEmptyStringGiven(): void
    {
        $this->expectExceptionMessage('Empty username');

        $this->provider->loadUserByUsername('');
    }
}
