<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Provider;

use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\Factory\WorkflowFactory;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;

/**
 */
class WorkflowProvider
{
    /**
     * @var WorkflowRepositoryInterface
     */
    private $repository;

    /**
     * @var WorkflowFactory
     */
    private $factory;

    /**
     * @param WorkflowRepositoryInterface $repository
     * @param WorkflowFactory             $factory
     */
    public function __construct(WorkflowRepositoryInterface $repository, WorkflowFactory $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
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
        $id = WorkflowId::fromCode($code);

        $workflow = $this->repository->load($id);
        if (null === $workflow) {
            $workflow = $this->factory->create($id, $code);
            $this->repository->save($workflow);
        }

        return $workflow;
    }
}
