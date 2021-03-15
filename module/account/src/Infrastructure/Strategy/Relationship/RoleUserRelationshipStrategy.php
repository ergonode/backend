<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Strategy\Relationship;

use Ergonode\Account\Domain\Query\AccountQueryInterface;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;
use Webmozart\Assert\Assert;

class RoleUserRelationshipStrategy implements RelationshipStrategyInterface
{
    private const MESSAGE = 'Object has active relationships with user %relations%';

    private AccountQueryInterface $query;

    public function __construct(AccountQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AggregateId $id): bool
    {
        return $id instanceof RoleId;
    }

    /**
     * {@inheritDoc}
     */
    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, RoleId::class);

        return new RelationshipGroup(self::MESSAGE, $this->query->findUserIdByRoleId($id));
    }
}
