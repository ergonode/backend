<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Infrastructure\Handler;

use Ergonode\Account\Application\Security\Security;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\BatchAction\Domain\Event\BatchActionFinishedEvent;
use Ergonode\BatchAction\Infrastructure\Handler\BatchActionFinishedEventHandler;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class BatchActionFinishedEventHandlerTest extends TestCase
{
    private TranslatorInterface $translator;

    private Security $security;

    private CommandBusInterface $commandBus;

    private BatchActionFinishedEvent $event;

    protected function setUp(): void
    {
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->security = $this->createMock(Security::class);
        $this->commandBus = $this->createMock(CommandBusInterface::class);
        $this->event = $this->createMock(BatchActionFinishedEvent::class);
    }

    public function testEventHandlingWithUser(): void
    {
        $handler = new BatchActionFinishedEventHandler($this->translator, $this->security, $this->commandBus);
        $user = $this->createMock(User::class);
        $this->translator->method('trans')->willReturn('test');
        $user->method('getId')->willReturn($this->createMock(UserId::class));
        $this->security->method('getUser')->willReturn($user);
        $this->commandBus->expects(self::once())->method('dispatch');
        $handler->__invoke($this->event);
    }

    public function testEventHandlingNoUser(): void
    {
        $this->expectException(\Symfony\Component\Security\Core\Exception\AuthenticationException::class);
        $this->expectExceptionMessage('User not set');

        $handler = new BatchActionFinishedEventHandler($this->translator, $this->security, $this->commandBus);
        $this->security->method('getUser')->willReturn(null);
        $this->commandBus->expects(self::never())->method('dispatch');
        $handler->__invoke($this->event);
    }
}
