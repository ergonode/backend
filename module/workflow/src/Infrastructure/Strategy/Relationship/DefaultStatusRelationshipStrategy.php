<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Strategy\Relationship;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\Workflow\Domain\Entity\StatusId;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\Query\TransitionQueryInterface;
use Ergonode\Workflow\Domain\Query\WorkflowQueryInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 */
class DefaultStatusRelationshipStrategy implements RelationshipStrategyInterface
{
    /**
     * @var WorkflowQueryInterface
     */
    private $query;

    /**
     * @param WorkflowQueryInterface $query
     */
    public function __construct(WorkflowQueryInterface $query)
    {
        $this->query = $query;
    }


    /**
     * {@inheritDoc}
     */
    public function supports(AbstractId $id): bool
    {
        return $id instanceof StatusId;
    }

    /**
     * {@inheritDoc}
     */
    public function getRelationships(AbstractId $id): array
    {
        if (!$this->supports($id)) {
            throw new UnexpectedTypeException($id, StatusId::class);
        }

        return $this->query->getWorkflowIdsWithDefaultStatus($id);
    }
}
