<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Infrastructure\Handler\Workflow;

use Ergonode\Workflow\Infrastructure\Handler\Workflow\DeleteWorkflowCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\Workflow\Domain\Command\Workflow\DeleteWorkflowCommand;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Core\Infrastructure\Model\RelationshipCollection;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;

class DeleteWorkflowCommandHandlerTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandHandling(): void
    {
        $command = $this->createMock(DeleteWorkflowCommand::class);

        $workflow = $this->createMock(Workflow::class);

        $relations = $this->createMock(RelationshipCollection::class);
        $relations->method('isEmpty')->willReturn(true);

        $repository = $this->createMock(WorkflowRepositoryInterface::class);
        $repository->expects(self::once())->method('delete');
        $repository->expects(self::once())->method('load')->willReturn($workflow);
        $resolver = $this->createMock(RelationshipsResolverInterface::class);
        $resolver->method('resolve')->willReturn($relations);

        $handler = new DeleteWorkflowCommandHandler($repository, $resolver);
        $handler->__invoke($command);
    }
}
