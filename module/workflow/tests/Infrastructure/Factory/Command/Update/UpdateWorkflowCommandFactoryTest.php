<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Infrastructure\Factory\Command\Update;

use Ergonode\Workflow\Infrastructure\Factory\Command\Update\UpdateWorkflowCommandFactory;
use PHPUnit\Framework\TestCase;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Application\Form\Model\Workflow\WorkflowFormModel;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormInterface;
use Ergonode\Workflow\Domain\Command\Workflow\UpdateWorkflowCommand;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;

class UpdateWorkflowCommandFactoryTest extends TestCase
{
    public function testSupported(): void
    {
        $commandFactory = new UpdateWorkflowCommandFactory();
        self::assertTrue($commandFactory->support(Workflow::TYPE));
        self::assertFalse($commandFactory->support('Any other type'));
    }

    /**
     * @throws \Exception
     */
    public function testCreation(): void
    {
        $workflowId = WorkflowId::generate();
        $code = 'code';
        $status = Uuid::uuid4()->toString();

        $data = $this->createMock(WorkflowFormModel::class);
        $data->code = $code;
        $data->statuses = [$status];

        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($data);

        $factory = new UpdateWorkflowCommandFactory();
        /** @var UpdateWorkflowCommand $result */
        $result = $factory->create($workflowId, $form);
        self::assertInstanceOf(UpdateWorkflowCommand::class, $result);
        self::assertSame($workflowId, $result->getId());
        self::assertSame($status, $result->getStatuses()[0]->getValue());
    }
}
