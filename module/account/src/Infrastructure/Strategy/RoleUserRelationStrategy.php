<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Strategy;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Account\Domain\Query\AccountQueryInterface;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Infrastructure\Strategy\RelationStrategyInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 */
class RoleUserRelationStrategy implements RelationStrategyInterface
{
    public const TYPE = 'user';

    /**
     * @var AccountQueryInterface
     */
    private $query;

    /**
     * @param AccountQueryInterface $query
     */
    public function __construct(AccountQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AbstractId $id): bool
    {
        return $id instanceof RoleId;
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * {@inheritDoc}
     */
    public function getRelations(AbstractId $id): array
    {
        if (!$this->supports($id)) {
            new UnexpectedTypeException($id, RoleId::class);
        }

        return $this->query->findUserIdByRoleId($id);
    }
}
