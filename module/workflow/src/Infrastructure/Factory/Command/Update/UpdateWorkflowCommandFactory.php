<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Factory\Command\Update;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Symfony\Component\Form\FormInterface;
use Ergonode\Workflow\Infrastructure\Factory\Command\UpdateWorkflowCommandFactoryInterface;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Application\Form\Model\Workflow\WorkflowFormModel;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Command\Workflow\UpdateWorkflowCommand;

/**
 */
class UpdateWorkflowCommandFactory implements UpdateWorkflowCommandFactoryInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function support(string $type): bool
    {
        return $type === Workflow::TYPE;
    }

    /**
     * @param WorkflowId    $id
     * @param FormInterface $form
     *
     * @return DomainCommandInterface
     *
     */
    public function create(WorkflowId $id, FormInterface $form): DomainCommandInterface
    {
        /** @var WorkflowFormModel $data */
        $data = $form->getData();
        $statuses = [];
        foreach ($data->statuses as $status) {
            $statuses[] = new StatusId($status);
        }

        return new UpdateWorkflowCommand(
            $id,
            $statuses
        );
    }
}
