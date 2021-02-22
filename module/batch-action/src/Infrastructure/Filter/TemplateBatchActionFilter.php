<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Filter;

use Doctrine\DBAL\Connection;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilter;
use Ergonode\BatchAction\Infrastructure\Provider\FilteredQueryBuilderInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class TemplateBatchActionFilter
{
    private FilteredQueryBuilderInterface $filteredQueryBuilder;

    private Connection $connection;

    public function __construct(
        FilteredQueryBuilderInterface $filteredQueryBuilder,
        Connection $connection
    ) {
        $this->filteredQueryBuilder = $filteredQueryBuilder;
        $this->connection = $connection;
    }

    /**
     * @return TemplateId[]
     */
    public function filter(?BatchActionFilter $filter): array
    {
        $result = false;

        if ($filter) {
            $filteredQueryBuilder = $this->filteredQueryBuilder->build($filter);

            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder->select('DISTINCT template_id')
                ->from('public.product', 'pt')
                ->join('pt', (sprintf('(%s)', $filteredQueryBuilder->getSQL())), 'pp', 'pp.id = pt.id')
                ->setParameters($filteredQueryBuilder->getParameters(), $filteredQueryBuilder->getParameterTypes());
            $result = $queryBuilder->execute()->fetchAll(\PDO::FETCH_COLUMN);
        }

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new TemplateId($item);
        }

        return $result;
    }
}
