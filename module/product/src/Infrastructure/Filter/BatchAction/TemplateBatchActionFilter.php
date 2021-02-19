<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Filter\BatchAction;

use Doctrine\DBAL\Connection;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilter;
use Ergonode\Product\Infrastructure\Provider\FilteredQueryBuilderProvider;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class TemplateBatchActionFilter
{
    private FilteredQueryBuilderProvider $filteredQueryBuilderProvider;

    private Connection $connection;

    public function __construct(
        FilteredQueryBuilderProvider $filteredQueryBuilderProvider,
        Connection $connection
    ) {
        $this->filteredQueryBuilderProvider = $filteredQueryBuilderProvider;
        $this->connection = $connection;
    }

    /**
     * @return TemplateId[]
     */
    public function filter(?BatchActionFilter $filter): array
    {
        $filteredQueryBuilder = $this->filteredQueryBuilderProvider->provide($filter);

        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->select('DISTINCT template_id')
            ->from('public.product', 'pt')
            ->join('pt', (sprintf('(%s)', $filteredQueryBuilder->getSQL())), 'pp', 'pp.id = pt.id')
            ->setParameters($filteredQueryBuilder->getParameters(), $filteredQueryBuilder->getParameterTypes());
        $result = $queryBuilder->execute()->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new TemplateId($item);
        }

        return $result;
    }
}
