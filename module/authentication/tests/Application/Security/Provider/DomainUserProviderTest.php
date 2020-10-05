<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Tests\Application\Security\Provider;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Authentication\Application\Security\Provider\DomainUserProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 */
class DomainUserProviderTest extends TestCase
{
    /**
     * @var UserRepositoryInterface|MockObject
     */
    private $mockUserRepository;
    private DomainUserProvider $provider;

    /**
     */
    protected function setUp(): void
    {
        $this->mockUserRepository = $this->createMock(UserRepositoryInterface::class);

        $this->provider = new DomainUserProvider(
            $this->mockUserRepository,
        );
    }

    /**
     */
    public function testShouldProvide(): void
    {
        $user = $this->createMock(User::class);
        $this->mockUserRepository
            ->method('load')
            ->willReturn($user);

        $result = $this->provider->loadUserByUsername((string) Uuid::uuid4());

        $this->assertSame($user, $result);
    }

    /**
     */
    public function testShouldThrowExceptionWhenNoSuchUser(): void
    {
        $id = (string) Uuid::uuid4();
        $this->mockUserRepository
            ->method('load')
            ->willReturn(null);
        $this->expectExceptionMessage("Username \"$id\" not found");

        $this->provider->loadUserByUsername($id);
    }

    /**
     */
    public function testShouldThrowExceptionWhenNoUUIDGiven(): void
    {
        $this->expectExceptionMessage('Invalid uuid format');

        $this->provider->loadUserByUsername('name');
    }

    /**
     */
    public function testShouldThrowExceptionWhenNoStringUsernameGiven(): void
    {
        $this->expectExceptionMessage('Username has to be a string');

        $this->provider->loadUserByUsername(1);
    }

    /**
     */
    public function testShouldThrowExceptionWhenEmptyUsernameGiven(): void
    {
        $this->expectExceptionMessage('Empty username');

        $this->provider->loadUserByUsername('');
    }
}
