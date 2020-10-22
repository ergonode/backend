<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Infrastructure\Handler\Status;

use Ergonode\Workflow\Infrastructure\Handler\Status\DeleteStatusCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;
use Ergonode\Workflow\Domain\Command\Status\DeleteStatusCommand;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\Core\Infrastructure\Model\RelationshipCollection;

class DeleteStatusCommandHandlerTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandHandling(): void
    {
        $command = $this->createMock(DeleteStatusCommand::class);

        $status = $this->createMock(Status::class);

        $relations = $this->createMock(RelationshipCollection::class);
        $relations->method('isEmpty')->willReturn(true);

        $repository = $this->createMock(StatusRepositoryInterface::class);
        $repository->expects(self::once())->method('delete');
        $repository->expects(self::once())->method('load')->willReturn($status);
        $resolver = $this->createMock(RelationshipsResolverInterface::class);
        $resolver->method('resolve')->willReturn($relations);

        $handler = new DeleteStatusCommandHandler($repository, $resolver);
        $handler->__invoke($command);
    }
}
