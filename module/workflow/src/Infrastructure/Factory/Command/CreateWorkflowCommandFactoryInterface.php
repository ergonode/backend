<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Factory\Command;

use Ergonode\Workflow\Domain\Command\Workflow\CreateWorkflowCommandInterface;
use Symfony\Component\Form\FormInterface;

interface CreateWorkflowCommandFactoryInterface
{
    public function support(string $type): bool;

    /**
     * @throws \Exception
     */
    public function create(FormInterface $form): CreateWorkflowCommandInterface;
}
