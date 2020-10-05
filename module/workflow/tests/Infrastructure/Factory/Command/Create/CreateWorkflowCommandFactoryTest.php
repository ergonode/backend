<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Infrastructure\Factory\Command\Create;

use Ergonode\Workflow\Infrastructure\Factory\Command\Create\CreateWorkflowCommandFactory;
use PHPUnit\Framework\TestCase;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Application\Form\Model\Workflow\WorkflowFormModel;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormInterface;
use Ergonode\Workflow\Domain\Command\Workflow\CreateWorkflowCommand;

/**
 */
class CreateWorkflowCommandFactoryTest extends TestCase
{
    /**
     */
    public function testSupported(): void
    {
        $commandFactory = new CreateWorkflowCommandFactory();
        self::assertTrue($commandFactory->support(Workflow::TYPE));
        self::assertFalse($commandFactory->support('Any other type'));
    }

    /**
     * @throws \Exception
     */
    public function testCreation(): void
    {
        $code = 'code';
        $status = Uuid::uuid4()->toString();

        $data = $this->createMock(WorkflowFormModel::class);
        $data->code = $code;
        $data->statuses = [$status];

        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($data);

        $factory = new CreateWorkflowCommandFactory();
        /** @var CreateWorkflowCommand $result */
        $result = $factory->create($form);
        self::assertInstanceOf(CreateWorkflowCommand::class, $result);
        self::assertSame($code, $result->getCode());
        self::assertSame($status, $result->getStatuses()[0]->getValue());
    }
}
