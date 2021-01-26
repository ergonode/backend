<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Infrastructure\Handler;

use Ergonode\BatchAction\Domain\Command\EndBatchActionProcessCommand;
use Ergonode\BatchAction\Infrastructure\Handler\EndBatchActionProcessCommandHandler;
use Ergonode\SharedKernel\Domain\Bus\EventBusInterface;
use PHPUnit\Framework\TestCase;

class EndBatchActionProcessCommandHandlerTest extends TestCase
{
    private EventBusInterface $eventBus;

    private EndBatchActionProcessCommand $command;

    protected function setUp(): void
    {
        $this->eventBus = $this->createMock(EventBusInterface::class);
        $this->command = $this->createMock(EndBatchActionProcessCommand::class);
    }

    public function testCommandHandlingProcessEnded(): void
    {
        $handler = new EndBatchActionProcessCommandHandler($this->eventBus);
        $this->eventBus->expects(self::once())->method('dispatch');
        $handler->__invoke($this->command);
    }
}
