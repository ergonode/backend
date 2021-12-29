<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Filter\BatchAction;

use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilterInterface;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\BatchAction\Infrastructure\Provider\BatchActionFilterIdsInterface;
use Ergonode\Product\Infrastructure\Provider\FilteredQueryBuilder;
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

    private FilteredQueryBuilder $filteredQueryBuilder;

    public function __construct(
        FilteredQueryBuilder $filteredQueryBuilder,
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
    public function filter(BatchActionFilterInterface $filter): array
    {
        $filteredQueryBuilder = $this->filteredQueryBuilder->build($filter);

        $result = $filteredQueryBuilder->execute()->fetchAll(\PDO::FETCH_COLUMN);

        return array_map(
            fn (string $item) => new ProductId($item),
            $result,
        );
    }
}
