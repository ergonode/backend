<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Strategy\Relationship;

use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Query\TransitionQueryInterface;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;
use Webmozart\Assert\Assert;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;

class StatusWorkflowRelationshipStrategy implements RelationshipStrategyInterface
{
    private const MESSAGE = 'Object has active relationships with workflow {relations}';

    private TransitionQueryInterface $query;

    private WorkflowProvider $provider;

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
     * @throws \Exception
     */
    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, StatusId::class);

        $workflow = $this->provider->provide();
        $workflowId = $workflow->getId();

        $result = [];
        if ($this->query->hasStatus($workflowId, $id)) {
            $result[] = $workflowId;
        }

        return new RelationshipGroup(self::MESSAGE, $result);
    }
}
