<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

class ProductBelongCategoryCondition implements ConditionInterface
{
    public const TYPE = 'PRODUCT_BELONG_CATEGORY_CONDITION';
    public const PHRASE = 'PRODUCT_BELONG_CATEGORY_CONDITION_PHRASE';

    public const BELONG_TO = 'BELONG_TO';
    public const NOT_BELONG_TO = 'NOT_BELONG_TO';

    /**
     * @var CategoryId[]
     */
    private array $category;

    private string $operator;

    /**
     * @param CategoryId[] $category
     */
    public function __construct(array $category, string $operator)
    {
        $this->category = $category;
        $this->operator = $operator;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return CategoryId[]
     */
    public function getCategory(): array
    {
        return $this->category;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }
}
