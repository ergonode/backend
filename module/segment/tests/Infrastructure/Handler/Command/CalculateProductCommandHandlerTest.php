<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Tests\Infrastructure\Handler\Command;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Segment\Domain\Command\CalculateProductCommand;
use Ergonode\Segment\Domain\Command\CreateSegmentCommand;
use Ergonode\Segment\Domain\Query\SegmentQueryInterface;
use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;
use Ergonode\Segment\Infrastructure\Handler\Command\CalculateProductCommandHandler;
use Ergonode\Segment\Infrastructure\Handler\Command\CreateSegmentCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CalculateProductCommandHandlerTest extends TestCase
{
    /**
     * @var CalculateProductCommand|MockObject
     */
    private $command;

    /**
     * @var SegmentQueryInterface|MockObject
     */
    private $query;

    /**
     * @var CommandBusInterface|MockObject
     */
    private $commandBus;

    /**
     */
    protected function setUp(): void
    {
        $this->command = $this->createMock(CalculateProductCommand::class);
        $this->command->expects($this->once())->method('getProductId')
            ->willReturn($this->createMock(ProductId::class));
        $this->query = $this->createMock(SegmentQueryInterface::class);
        $this->commandBus = $this->createMock(CommandBusInterface::class);
    }

    /**
     * @throws \Exception
     */
    public function testCommandHandling(): void
    {


        $handler = new CalculateProductCommandHandler($this->query, $this->commandBus);
        $handler->__invoke($this->command);
    }
}
