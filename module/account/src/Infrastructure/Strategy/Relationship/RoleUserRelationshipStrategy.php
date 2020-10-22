<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Strategy\Relationship;

use Ergonode\Account\Domain\Query\AccountQueryInterface;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class RoleUserRelationshipStrategy implements RelationshipStrategyInterface
{
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
    public function getRelationships(AggregateId $id): array
    {
        if (!$this->supports($id)) {
            new UnexpectedTypeException($id, RoleId::class);
        }

        return $this->query->findUserIdByRoleId($id);
    }
}
