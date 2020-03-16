<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Provider;

use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Factory\WorkflowFactory;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Ergonode\Workflow\Domain\Query\WorkflowQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;

/**
 */
class WorkflowProvider
{
    /**
     * @var WorkflowRepositoryInterface
     */
    private WorkflowRepositoryInterface $repository;

    /**
     * @var WorkflowFactory
     */
    private WorkflowFactory $factory;

    /**
     * @var WorkflowQueryInterface
     */
    private WorkflowQueryInterface $query;

    /**
     * @param WorkflowRepositoryInterface $repository
     * @param WorkflowFactory             $factory
     * @param WorkflowQueryInterface      $query
     */
    public function __construct(
        WorkflowRepositoryInterface $repository,
        WorkflowFactory $factory,
        WorkflowQueryInterface $query
    ) {
        $this->repository = $repository;
        $this->factory = $factory;
        $this->query = $query;
    }

    /**
     * @param string $code
     *
     * @return Workflow
     *
     * @throws \Exception
     */
    public function provide(string $code = Workflow::DEFAULT): Workflow
    {
        $workflow = null;
        $id = $this->query->findWorkflowIdByCode($code);

        if ($id) {
            $workflow = $this->repository->load($id);
        }

        if (!$workflow) {
            $workflow = $this->factory->create(WorkflowId::generate(), $code);
            $this->repository->save($workflow);
        }

        return $workflow;
    }
}
