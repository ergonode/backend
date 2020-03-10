<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Tests\Infrastructure\Handler\Command;

use Ergonode\Segment\Domain\Command\CreateSegmentCommand;
use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;
use Ergonode\Segment\Infrastructure\Handler\Command\CreateSegmentCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateSegmentCommandHandlerTest extends TestCase
{
    /**
     * @var CreateSegmentCommand|MockObject
     */
    private $command;

    /**
     * @var SegmentRepositoryInterface|MockObject
     */
    private $repository;

    /**
     */
    protected function setUp(): void
    {
        $this->command = $this->createMock(CreateSegmentCommand::class);
        $this->repository = $this->createMock(SegmentRepositoryInterface::class);
    }

    /**
     */
    public function testCommandHandling(): void
    {
        $this->repository->expects($this->once())->method('save');

        $handler = new CreateSegmentCommandHandler($this->repository);
        $handler->__invoke($this->command);
    }
}
