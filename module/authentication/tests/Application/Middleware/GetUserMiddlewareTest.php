<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Tests\Application\Middleware;

use Ergonode\Account\Application\Security\Security;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Authentication\Application\Middleware\GetUserMiddleware;
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
        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn($this->createMock(UserId::class));
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
