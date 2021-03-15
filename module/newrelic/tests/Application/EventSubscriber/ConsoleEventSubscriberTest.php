<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\NewRelic\Tests\Application\EventSubscriber;

use Ergonode\NewRelic\Application\EventSubscriber\ConsoleEventSubscriber;
use Ergonode\NewRelic\Application\NewRelic\NewRelicInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleEventSubscriberTest extends TestCase
{
    /**
     * @var NewRelicInterface|MockObject
     */
    private $mockNewRelic;
    private ConsoleEventSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->mockNewRelic = $this->createMock(NewRelicInterface::class);

        $this->subscriber = new ConsoleEventSubscriber(
            $this->mockNewRelic,
        );
    }

    public function testShouldSetTransactionName(): void
    {
        $this->mockNewRelic->expects($this->once())->method('nameTransaction');

        $command = $this->createMock(Command::class);
        $command->method('getName')->willReturn('commandName');

        $this->subscriber->onConsoleCommand(
            new ConsoleCommandEvent(
                $command,
                $this->createMock(InputInterface::class),
                $this->createMock(OutputInterface::class),
            ),
        );
    }

    public function testShouldNotSetTransactionNameForEmptyCommandName(): void
    {
        $this->mockNewRelic->expects($this->never())->method('nameTransaction');

        $command = $this->createMock(Command::class);
        $command->method('getName')->willReturn(null);

        $this->subscriber->onConsoleCommand(
            new ConsoleCommandEvent(
                $command,
                $this->createMock(InputInterface::class),
                $this->createMock(OutputInterface::class),
            ),
        );
    }

    public function testShouldNotSetTransactionNameForEmptyCommand(): void
    {
        $this->mockNewRelic->expects($this->never())->method('nameTransaction');

        $this->subscriber->onConsoleCommand(
            new ConsoleCommandEvent(
                null,
                $this->createMock(InputInterface::class),
                $this->createMock(OutputInterface::class),
            ),
        );
    }
}
