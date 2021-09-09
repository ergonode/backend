<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
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
    private const ONE_MESSAGE = 'Status has a relation with a workflow';
    private const MULTIPLE_MESSAGE = 'Status has %count% relations with some workflows';

    private TransitionQueryInterface $query;

    private WorkflowProvider $provider;

    public function __construct(TransitionQueryInterface $query, WorkflowProvider $provider)
    {
        $this->query = $query;
        $this->provider = $provider;
    }

    public function supports(AggregateId $id): bool
    {
        return $id instanceof StatusId;
    }

    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, StatusId::class);

        $workflow = $this->provider->provide();
        $workflowId = $workflow->getId();

        $relations = [];
        if ($this->query->hasStatus($workflowId, $id)) {
            $relations[] = $workflowId;
        }

        $message = count($relations) === 1 ? self::ONE_MESSAGE : self::MULTIPLE_MESSAGE;

        return new RelationshipGroup($message, $relations);
    }
}
