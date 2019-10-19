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
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 */
class StatusWorkflowRelationshipStrategy implements RelationshipStrategyInterface
{
    /**
     * @var TransitionQueryInterface
     */
    private $query;

    /**
     * @param TransitionQueryInterface $query
     */
    public function __construct(TransitionQueryInterface $query)
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

        $workflowId = WorkflowId::fromCode(Workflow::DEFAULT);

        if ($this->query->hasStatus($workflowId, $id)) {
            return [$workflowId];
        }

        return [];
    }
}
