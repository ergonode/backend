<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Infrastructure\Handler;

use Ergonode\BatchAction\Domain\Command\EndBatchActionCommand;
use Ergonode\BatchAction\Infrastructure\Handler\EndBatchActionCommandHandler;
use Ergonode\SharedKernel\Domain\Bus\DomainEventBusInterface;
use PHPUnit\Framework\TestCase;

class EndBatchActionCommandHandlerTest extends TestCase
{
    private DomainEventBusInterface $eventBus;

    private EndBatchActionCommand $command;

    protected function setUp(): void
    {
        $this->eventBus = $this->createMock(DomainEventBusInterface::class);
        $this->command = $this->createMock(EndBatchActionCommand::class);
    }

    public function testCommandHandlingEnded(): void
    {
        $handler = new EndBatchActionCommandHandler($this->eventBus);
        $this->eventBus->expects(self::once())->method('dispatch');
        $handler->__invoke($this->command);
    }
}
