<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use JMS\Serializer\Annotation as JMS;

class ProductBelongCategoryTreeCondition implements ConditionInterface
{
    public const TYPE = 'PRODUCT_BELONG_CATEGORY_TREE_CONDITION';
    public const PHRASE = 'PRODUCT_BELONG_CATEGORY_TREE_CONDITION_PHRASE';

    public const BELONG_TO = 'BELONG_TO';
    public const NOT_BELONG_TO = 'NOT_BELONG_TO';

    /**
     * @var CategoryTreeId[]
     *
     * @JMS\Type("array<Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId>")
     */
    private array $tree;

    /**
     * @JMS\Type("string")
     */
    private string $operator;

    /**
     * @param CategoryTreeId[] $tree
     */
    public function __construct(array $tree, string $operator)
    {
        $this->tree = $tree;
        $this->operator = $operator;
    }

    /**
     * {@inheritDoc}
     *
     * @JMS\VirtualProperty()
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return CategoryTreeId[]
     */
    public function getTree(): array
    {
        return $this->tree;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }
}
