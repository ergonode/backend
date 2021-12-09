<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Factory\Command\Create;

use Ergonode\Workflow\Domain\Command\Workflow\CreateWorkflowCommandInterface;
use Symfony\Component\Form\FormInterface;
use Ergonode\Workflow\Domain\Command\Workflow\CreateWorkflowCommand;
use Ergonode\Workflow\Application\Form\Model\Workflow\WorkflowFormModel;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Infrastructure\Factory\Command\CreateWorkflowCommandFactoryInterface;
use Ergonode\Workflow\Domain\Entity\Workflow;

class CreateWorkflowCommandFactory implements CreateWorkflowCommandFactoryInterface
{
    public function support(string $type): bool
    {
        return $type === Workflow::TYPE;
    }

    /**
     * @throws \Exception
     */
    public function create(FormInterface $form): CreateWorkflowCommandInterface
    {
        /** @var WorkflowFormModel $data */
        $data = $form->getData();
        $statuses = [];
        foreach ($data->statuses as $status) {
            $statuses[] = new StatusId($status);
        }
        $defaultStatus = new StatusId($data->defaultId);

        return new CreateWorkflowCommand(
            WorkflowId::generate(),
            $data->code,
            $defaultStatus,
            $statuses,
        );
    }
}
