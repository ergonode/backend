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
    private const MESSAGE = 'Object has active relationships with workflow transition %relations%';

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


        return new RelationshipGroup(self::MESSAGE, $this->query->findIdByConditionSetId($id));
    }
}
