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
use Webmozart\Assert\Assert;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;

class StatusWorkflowRelationshipStrategy implements RelationshipStrategyInterface
{
    private const ONE_MESSAGE = 'Status has a relation with a workflow';
    private const MULTIPLE_MESSAGE = 'Status has %count% relations with some workflows';

    private TransitionQueryInterface $query;

    public function __construct(TransitionQueryInterface $query)
    {
        $this->query = $query;
    }

    public function supports(AggregateId $id): bool
    {
        return $id instanceof StatusId;
    }

    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, StatusId::class);

        $relations = $this->query->findWorkflowsByStatus($id);

        $message = count($relations) === 1 ? self::ONE_MESSAGE : self::MULTIPLE_MESSAGE;

        return new RelationshipGroup($message, $relations);
    }
}
