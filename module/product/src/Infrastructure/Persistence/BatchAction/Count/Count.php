<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\BatchAction\Count;

use Doctrine\DBAL\Connection;
use Ergonode\BatchAction\Domain\Count\CountInterface;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilterInterface;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\Product\Infrastructure\Provider\FilteredQueryBuilder;

final class Count implements CountInterface
{
    private const TYPES = [
        'product_edit',
        'product_delete',
    ];

    private FilteredQueryBuilder $filteredQueryBuilder;
    private Connection $connection;

    public function __construct(FilteredQueryBuilder $filteredQueryBuilder, Connection $connection)
    {
        $this->filteredQueryBuilder = $filteredQueryBuilder;
        $this->connection = $connection;
    }

    public function supports(BatchActionType $type): bool
    {
        return in_array($type->getValue(), self::TYPES);
    }

    public function count(BatchActionType $type, BatchActionFilterInterface $filter): int
    {
        if (!$this->supports($type)) {
            throw new \RuntimeException("{$type->getValue()} type unsupported.");
        }

        $filteredQueryBuilder = $this->filteredQueryBuilder->build($filter);

        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->select('COUNT (*)')
            ->from("({$filteredQueryBuilder->getSQL()})", 'sq')
            ->setParameters($filteredQueryBuilder->getParameters(), $filteredQueryBuilder->getParameterTypes());

        return $queryBuilder->execute()->fetch(\PDO::FETCH_COLUMN);
    }
}
