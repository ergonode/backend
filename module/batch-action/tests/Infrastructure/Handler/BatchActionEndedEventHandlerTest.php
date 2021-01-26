<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Infrastructure\Handler;

use Ergonode\Account\Application\Security\Security;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\BatchAction\Domain\Event\BatchActionEndedEvent;
use Ergonode\BatchAction\Infrastructure\Handler\BatchActionEndedEventHandler;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class BatchActionEndedEventHandlerTest extends TestCase
{
    private TranslatorInterface $translator;

    private Security $security;

    private CommandBusInterface $commandBus;

    private BatchActionEndedEvent $event;

    protected function setUp(): void
    {
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->security = $this->createMock(Security::class);
        $this->commandBus = $this->createMock(CommandBusInterface::class);
        $this->event = $this->createMock(BatchActionEndedEvent::class);
    }

    public function testEventHandlingWithUser(): void
    {
        $handler = new BatchActionEndedEventHandler($this->translator, $this->security, $this->commandBus);
        $user = $this->createMock(User::class);
        $this->translator->method('trans')->willReturn('test');
        $user->method('getId')->willReturn($this->createMock(UserId::class));
        $this->security->method('getUser')->willReturn($user);
        $this->commandBus->expects(self::once())->method('dispatch');
        $handler->__invoke($this->event);
    }

    public function testEventHandlingNoUser(): void
    {
        $handler = new BatchActionEndedEventHandler($this->translator, $this->security, $this->commandBus);
        $this->security->method('getUser')->willReturn(null);
        $this->commandBus->expects(self::never())->method('dispatch');
        $handler->__invoke($this->event);
    }
}
