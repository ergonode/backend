<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Factory\Command;

use Ergonode\Workflow\Domain\Command\Workflow\UpdateWorkflowCommandInterface;
use Symfony\Component\Form\FormInterface;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;

interface UpdateWorkflowCommandFactoryInterface
{
    public function support(string $type): bool;

    public function create(WorkflowId $id, FormInterface $form): UpdateWorkflowCommandInterface;
}
