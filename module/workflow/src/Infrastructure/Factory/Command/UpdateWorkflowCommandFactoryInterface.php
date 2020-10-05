<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Factory\Command;

use Symfony\Component\Form\FormInterface;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;

/**
 */
interface UpdateWorkflowCommandFactoryInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function support(string $type): bool;

    /**
     * @param WorkflowId    $id
     * @param FormInterface $form
     *
     * @return DomainCommandInterface
     */
    public function create(WorkflowId $id, FormInterface $form): DomainCommandInterface;
}
