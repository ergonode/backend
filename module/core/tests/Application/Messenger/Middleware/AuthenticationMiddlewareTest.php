<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Application\Messenger\Middleware;

use Ergonode\Core\Application\Messenger\Middleware\AuthenticationMiddleware;
use Ergonode\Core\Application\Messenger\Stamp\UserStamp;
use Ergonode\Core\Application\Security\User\CachedUser;
use Ergonode\Core\Domain\User\UserInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use Ergonode\Core\Domain\ValueObject\Language;

class AuthenticationMiddlewareTest extends TestCase
{
    private TokenStorageInterface $tokenStorage;

    private Envelope $envelope1;

    private AuthenticationMiddleware $authenticationMiddleware;

    private StackInterface $stack;


    public function setUp(): void
    {
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->envelope1 = new Envelope($this->createMock(\stdClass::class));
        $this->authenticationMiddleware = new AuthenticationMiddleware(
            $this->tokenStorage
        );
        $this->stack = $this->createMock(StackInterface::class);
    }

    public function testHandleNoReceivedStamp(): void
    {
        $envelope2 = new Envelope($this->createMock(\stdClass::class));
        $nextMiddleware = $this->createMock(MiddlewareInterface::class);
        $this->stack->method('next')->willReturn($nextMiddleware);
        $nextMiddleware->method('handle')->willReturn($envelope2);
        $this->tokenStorage->expects(self::never())->method('setToken');

        $result = $this->authenticationMiddleware->handle($this->envelope1, $this->stack);

        self::assertSame($envelope2, $result);
    }

    public function testHandleReceivedStamp(): void
    {
        $user = new CachedUser(
            $this->createMock(UserId::class),
            'Name',
            'Surname',
            $this->createMock(RoleId::class),
            $this->createMock(Email::class),
            $this->createMock(Language::class),
            true
        );

        $envelope = $this->envelope1->with(new ReceivedStamp('transport'));
        $envelope = $envelope->with(new UserStamp($user));
        $user = $this->createMock(UserInterface::class);
        $user->method('getRoles')->willReturn([]);
        $nextMiddleware = $this->createMock(MiddlewareInterface::class);
        $this->stack->method('next')->willReturn($nextMiddleware);
        $envelope2 = new Envelope($this->createMock(\stdClass::class));
        $nextMiddleware->method('handle')->willReturn($envelope2);
        $this->tokenStorage->expects(self::exactly(3))->method('setToken');

        $result = $this->authenticationMiddleware->handle($envelope, $this->stack);

        self::assertSame($envelope2, $result);
    }

    public function testHandleNoUserStamp(): void
    {
        $envelope = $this->envelope1->with(new ReceivedStamp('transport'));
        $nextMiddleware = $this->createMock(MiddlewareInterface::class);
        $this->stack->method('next')->willReturn($nextMiddleware);
        $envelope2 = new Envelope($this->createMock(\stdClass::class));
        $nextMiddleware->method('handle')->willReturn($envelope2);
        $this->tokenStorage->expects(self::exactly(2))->method('setToken');

        $result = $this->authenticationMiddleware->handle($envelope, $this->stack);

        self::assertSame($envelope2, $result);
    }

    public function testHandleNextMiddlewareError(): void
    {
        $this->expectException(\Throwable::class);
        $nextMiddleware = $this->createMock(MiddlewareInterface::class);
        $nextMiddleware->method('handle')->willThrowException($this->createMock(\Throwable::class));

        $this->authenticationMiddleware->handle($this->envelope1, $this->stack);

        $this->stack->method('next')->willReturn($nextMiddleware);
        $this->tokenStorage->expects(self::once())->method('setToken');
    }
}
