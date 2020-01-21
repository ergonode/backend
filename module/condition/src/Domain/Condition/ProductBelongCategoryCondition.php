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

    /**
     * @var CategoryId
     *
     * @JMS\Type("Ergonode\Category\Domain\Entity\CategoryId")
     */
    private CategoryId $categoryId;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $operator;

    /**
     * ProductBelongCategoryCondition constructor.
     * @param CategoryId $categoryId
     * @param string     $operator
     */
    public function __construct(CategoryId $categoryId, string $operator)
    {
        $this->categoryId = $categoryId;
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
    public function getCategoryId(): CategoryId
    {
        return $this->categoryId;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }
}
