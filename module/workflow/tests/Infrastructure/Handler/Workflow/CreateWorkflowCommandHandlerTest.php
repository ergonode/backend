<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Infrastructure\Handler\Workflow;

use Ergonode\Workflow\Infrastructure\Handler\Workflow\CreateWorkflowCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\Workflow\Domain\Command\Workflow\CreateWorkflowCommand;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Ergonode\Workflow\Domain\Factory\WorkflowFactory;

class CreateWorkflowCommandHandlerTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandHandling(): void
    {
        $command = $this->createMock(CreateWorkflowCommand::class);

        $repository = $this->createMock(WorkflowRepositoryInterface::class);
        $repository->expects(self::once())->method('save');
        $factory = $this->createMock(WorkflowFactory::class);
        $factory->expects(self::once())->method('create');

        $handler = new CreateWorkflowCommandHandler($repository, $factory);
        $handler->__invoke($command);
    }
}
