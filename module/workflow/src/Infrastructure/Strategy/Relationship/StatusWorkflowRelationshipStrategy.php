<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Strategy\Relationship;

use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Query\TransitionQueryInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;

/**
 */
class StatusWorkflowRelationshipStrategy implements RelationshipStrategyInterface
{
    /**
     * @var TransitionQueryInterface
     */
    private TransitionQueryInterface $query;

    /**
     * @var WorkflowProvider
     */
    private WorkflowProvider $provider;

    /**
     * @param TransitionQueryInterface $query
     * @param WorkflowProvider         $provider
     */
    public function __construct(TransitionQueryInterface $query, WorkflowProvider $provider)
    {
        $this->query = $query;
        $this->provider = $provider;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AggregateId $id): bool
    {
        return $id instanceof StatusId;
    }

    /**
     * @param AggregateId $statusId
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getRelationships(AggregateId $statusId): array
    {
        if (!$this->supports($statusId)) {
            throw new UnexpectedTypeException($statusId, StatusId::class);
        }

        $workflow = $this->provider->provide();
        $workflowId = $workflow->getId();

        if ($this->query->hasStatus($workflowId, $statusId)) {
            return [$workflowId];
        }

        return [];
    }
}
