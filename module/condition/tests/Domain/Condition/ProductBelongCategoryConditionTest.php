<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

namespace Ergonode\Condition\Tests\Domain\Condition;

use Ergonode\Condition\Domain\Condition\ProductBelongCategoryCondition;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductBelongCategoryConditionTest extends TestCase
{

    /**
     */
    public function testConditionCreation(): void
    {
        /** @var CategoryId | MockObject $categoryId */
        $categoryId = $this->createMock(CategoryId::class);
        $operator = 'equal';

        $condition = new ProductBelongCategoryCondition([$categoryId], $operator);

        self::assertSame([$categoryId], $condition->getCategory());
        self::assertSame($operator, $condition->getOperator());
        self::assertSame(ProductBelongCategoryCondition::TYPE, $condition->getType());
    }
}
