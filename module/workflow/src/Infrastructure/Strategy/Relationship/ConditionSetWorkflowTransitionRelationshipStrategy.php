<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Strategy\Relationship;

use Ergonode\Core\Infrastructure\Model\RelationshipGroup;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Workflow\Domain\Query\TransitionConditionSetQueryInterface;
use Webmozart\Assert\Assert;

class ConditionSetWorkflowTransitionRelationshipStrategy implements RelationshipStrategyInterface
{
    private const ONE_MESSAGE = 'Condition set has a relation with a status transition';
    private const MULTIPLE_MESSAGE = 'Condition set has %count% relations with some status transitions';

    private TransitionConditionSetQueryInterface $query;

    public function __construct(TransitionConditionSetQueryInterface $query)
    {
        $this->query = $query;
    }

    public function supports(AggregateId $id): bool
    {
        return $id instanceof ConditionSetId;
    }

    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, ConditionSetId::class);

        $relations = $this->query->findIdByConditionSetId($id);
        $message = count($relations) === 1 ? self::ONE_MESSAGE : self::MULTIPLE_MESSAGE;

        return new RelationshipGroup($message, $relations);
    }
}
