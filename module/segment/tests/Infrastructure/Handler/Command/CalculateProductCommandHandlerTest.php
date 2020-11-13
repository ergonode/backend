<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Tests\Infrastructure\Handler\Command;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Segment\Domain\Command\CalculateProductCommand;
use Ergonode\Segment\Domain\Query\SegmentQueryInterface;
use Ergonode\Segment\Infrastructure\Handler\Command\CalculateProductCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\Segment\Infrastructure\Service\SegmentProductService;

class CalculateProductCommandHandlerTest extends TestCase
{
    private CalculateProductCommand $command;

    private SegmentQueryInterface $query;

    private SegmentProductService $service;

    /**
     * @var CommandBusInterface|MockObject
     */
    private $commandBus;

    protected function setUp(): void
    {
        $productId = $this->createMock(ProductId::class);

        $this->command = $this->createMock(CalculateProductCommand::class);
        $this->command->expects(self::once())->method('getProductId')->willReturn($productId);
        $this->query = $this->createMock(SegmentQueryInterface::class);
        $this->commandBus = $this->createMock(CommandBusInterface::class);
        $this->service = $this->createMock(SegmentProductService::class);
    }

    /**
     * @throws \Exception
     */
    public function testCommandHandling(): void
    {
        $handler = new CalculateProductCommandHandler($this->query, $this->commandBus, $this->service);
        $handler->__invoke($this->command);
    }
}
