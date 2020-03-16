<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Tests\Infrastructure\Handler\Command;

use Ergonode\Core\Infrastructure\Model\RelationshipCollection;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Segment\Domain\Command\DeleteSegmentCommand;
use Ergonode\Segment\Domain\Entity\Segment;
use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;
use Ergonode\Segment\Infrastructure\Handler\Command\DeleteSegmentCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class DeleteSegmentCommandHandlerTest extends TestCase
{
    /**
     * @var DeleteSegmentCommand|MockObject
     */
    private $command;

    /**
     * @var SegmentRepositoryInterface|MockObject
     */
    private $repository;

    /**
     * @var RelationshipsResolverInterface|MockObject
     */
    private $resolver;

    /**
     */
    protected function setUp(): void
    {
        $this->command = $this->createMock(DeleteSegmentCommand::class);
        $this->repository = $this->createMock(SegmentRepositoryInterface::class);
        $this->resolver = $this->createMock(RelationshipsResolverInterface::class);
        $this->resolver->method('resolve')->willReturn(new RelationshipCollection());
    }

    /**
     * @throws \Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException
     */
    public function testCommandHandling(): void
    {
        $this->repository->expects($this->once())->method('load')->willReturn($this->createMock(Segment::class));
        $this->repository->expects($this->once())->method('delete');

        $handler = new DeleteSegmentCommandHandler($this->repository, $this->resolver);
        $handler->__invoke($this->command);
    }
}
