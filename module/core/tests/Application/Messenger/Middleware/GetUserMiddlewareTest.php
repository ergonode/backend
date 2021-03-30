<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Application\Messenger\Middleware;

use Ergonode\Core\Application\Messenger\Middleware\GetUserMiddleware;
use Ergonode\Core\Application\Security\Security;
use Ergonode\Core\Domain\User\UserInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class GetUserMiddlewareTest extends TestCase
{
    public function testHandle(): void
    {
        $security = $this->createMock(Security::class);
        $envelope1 = new Envelope($this->createMock(\stdClass::class));
        $user = $this->createMock(UserInterface::class);
        $user->method('getId')->willReturn($this->createMock(UserId::class));
        $user->method('isActive')->willReturn(true);
        $security->method('getUser')->willReturn($user);
        $nextMiddleware = $this->createMock(MiddlewareInterface::class);
        $stack = $this->createMock(StackInterface::class);
        $stack->method('next')->willReturn($nextMiddleware);
        $envelope2 = new Envelope($this->createMock(\stdClass::class));
        $nextMiddleware->method('handle')->willReturn($envelope2);

        $middleware = new GetUserMiddleware($security);
        $result = $middleware->handle($envelope1, $stack);
        self::assertSame($envelope2, $result);
    }
}
