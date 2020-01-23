<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Condition\Domain\ConditionInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductBelongCategoryCondition implements ConditionInterface
{
    public const TYPE = 'PRODUCT_BELONG_CATEGORY_CONDITION';
    public const PHRASE = 'PRODUCT_BELONG_CATEGORY_CONDITION_PHRASE';

    public const BELONG_TO = 'BELONG_TO';
    public const NOT_BELONG_TO = 'NOT_BELONG_TO';

    /**
     * @var CategoryId
     *
     * @JMS\Type("Ergonode\Category\Domain\Entity\CategoryId")
     */
    private CategoryId $category;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $operator;

    /**
     * ProductBelongCategoryCondition constructor.
     * @param CategoryId $category
     * @param string     $operator
     */
    public function __construct(CategoryId $category, string $operator)
    {
        $this->category = $category;
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
     * @return CategoryId
     */
    public function getCategory(): CategoryId
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }
}
