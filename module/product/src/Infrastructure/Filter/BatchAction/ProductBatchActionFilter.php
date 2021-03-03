<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Filter\BatchAction;

use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilter;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\BatchAction\Infrastructure\Provider\BatchActionFilterIdsInterface;
use Ergonode\BatchAction\Infrastructure\Provider\FilteredQueryBuilderInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class ProductBatchActionFilter implements BatchActionFilterIdsInterface
{
    private const TYPES = [
        'product_delete',
        'product_edit',
    ];

    /**
     * @var string []
     */
    private array $types;

    private FilteredQueryBuilderInterface $filteredQueryBuilder;

    public function __construct(
        FilteredQueryBuilderInterface $filteredQueryBuilder,
        ?array $types = []
    ) {
        $this->filteredQueryBuilder = $filteredQueryBuilder;
        $this->types = $types ?: self::TYPES;
    }

    public function supports(BatchActionType $type): bool
    {
        return in_array($type->getValue(), $this->types, true);
    }

    /**
     * @return ProductId[]
     */
    public function filter(BatchActionFilter $filter): array
    {
        $filteredQueryBuilder = $this->filteredQueryBuilder->build($filter);

        $result = $filteredQueryBuilder->execute()->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new ProductId($item);
        }

        return $result;
    }
}
