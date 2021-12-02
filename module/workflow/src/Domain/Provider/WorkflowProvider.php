<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Provider;

use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Ergonode\Workflow\Domain\Query\WorkflowQueryInterface;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;
use Ergonode\Core\Domain\ValueObject\Language;
use Webmozart\Assert\Assert;

class WorkflowProvider implements WorkflowProviderInterface
{
    private WorkflowRepositoryInterface $repository;

    private WorkflowQueryInterface $query;

    public function __construct(
        WorkflowRepositoryInterface $repository,
        WorkflowQueryInterface $query
    ) {
        $this->repository = $repository;
        $this->query = $query;
    }

    /**
     * @throws \Exception
     */
    public function provide(?Language $language = null): AbstractWorkflow
    {
        $workflow = null;
        $id = $this->query->findWorkflowIdByCode(AbstractWorkflow::DEFAULT);

        if ($id) {
            $workflow = $this->repository->load($id);
        }

        Assert::isInstanceOf($workflow, AbstractWorkflow::class, 'Can\'t provide workflow');

        return $workflow;
    }
}
