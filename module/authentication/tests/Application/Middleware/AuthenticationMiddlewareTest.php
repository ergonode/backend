<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Tests\Application\Middleware;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Authentication\Application\Middleware\AuthenticationMiddleware;
use Ergonode\Authentication\Application\Stamp\UserStamp;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuthenticationMiddlewareTest extends TestCase
{
    private TokenStorageInterface $tokenStorage;

    private UserRepositoryInterface $userRepository;

    private Envelope $envelope1;

    private AuthenticationMiddleware $authenticationMiddleware;

    private StackInterface $stack;

    private LoggerInterface $logger;


    public function setUp(): void
    {
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->envelope1 = new Envelope($this->createMock(\stdClass::class));
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->authenticationMiddleware = new AuthenticationMiddleware(
            $this->tokenStorage,
            $this->userRepository,
            $this->logger
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
        $envelope = $this->envelope1->with(new ReceivedStamp('transport'));
        $envelope = $envelope->with(new UserStamp($this->createMock(UserId::class)));
        $user = $this->createMock(User::class);
        $user->method('getRoles')->willReturn([]);
        $this->userRepository->method('load')->willReturn($user);
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

    public function testHandleReceivedStampNoUser(): void
    {
        $envelope = $this->envelope1->with(new ReceivedStamp('transport'));
        $envelope = $envelope->with(new UserStamp(UserId::generate()));
        $this->userRepository->method('load')->willReturn(null);
        $nextMiddleware = $this->createMock(MiddlewareInterface::class);
        $this->stack->method('next')->willReturn($nextMiddleware);
        $envelope2 = new Envelope($this->createMock(\stdClass::class));
        $nextMiddleware->method('handle')->willReturn($envelope2);
        $this->logger->expects(self::once())->method('error');
        $this->tokenStorage->expects(self::exactly(2))->method('setToken');

        $this->authenticationMiddleware->handle($envelope, $this->stack);
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
