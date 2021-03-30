<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Infrastructure\Handler;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\BatchAction\Domain\Entity\BatchAction;
use Ergonode\BatchAction\Domain\Event\BatchActionEndedEvent;
use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Ergonode\BatchAction\Infrastructure\Handler\BatchActionEndedEventHandler;
use Ergonode\Core\Application\Security\Security;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class BatchActionEndedEventHandlerTest extends TestCase
{
    private BatchActionRepositoryInterface $batchActionRepository;

    private Security $security;

    private CommandBusInterface $commandBus;

    private TranslatorInterface $translator;

    private BatchActionEndedEvent $event;

    protected function setUp(): void
    {
        $this->batchActionRepository = $this->createMock(BatchActionRepositoryInterface::class);
        $this->security = $this->createMock(Security::class);
        $this->commandBus = $this->createMock(CommandBusInterface::class);
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->event = $this->createMock(BatchActionEndedEvent::class);
    }

    public function testEventHandlingWithUserAndAction(): void
    {
        $handler = new BatchActionEndedEventHandler(
            $this->security,
            $this->commandBus,
            $this->batchActionRepository,
            $this->translator
        );
        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn($this->createMock(UserId::class));
        $this->translator->method('trans')->willReturn('test');
        $this->security->method('getUser')->willReturn($user);
        $this->batchActionRepository->method('load')->willReturn($this->createMock(BatchAction::class));
        $this->commandBus->expects(self::once())->method('dispatch');
        $handler->__invoke($this->event);
    }

    public function testEventHandlingNoUser(): void
    {
        $handler = new BatchActionEndedEventHandler(
            $this->security,
            $this->commandBus,
            $this->batchActionRepository,
            $this->translator
        );
        $this->security->method('getUser')->willReturn(null);
        $this->commandBus->expects(self::never())->method('dispatch');
        $handler->__invoke($this->event);
    }
}
