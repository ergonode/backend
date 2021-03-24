<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Tests\Domain\Condition;

use Ergonode\Condition\Domain\Condition\ProductBelongCategoryCondition;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductBelongCategoryConditionTest extends TestCase
{

    public function testConditionCreation(): void
    {
        /** @var CategoryId | MockObject $categoryId */
        $categoryId = $this->createMock(CategoryId::class);
        $operator = 'equal';

        $condition = new ProductBelongCategoryCondition([$categoryId], $operator);

        $this->assertSame([$categoryId], $condition->getCategory());
        $this->assertSame($operator, $condition->getOperator());
        $this->assertSame(ProductBelongCategoryCondition::TYPE, $condition->getType());
    }
}
