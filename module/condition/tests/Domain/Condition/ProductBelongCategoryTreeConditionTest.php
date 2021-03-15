<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Tests\Domain\Condition;

use Ergonode\Condition\Domain\Condition\ProductBelongCategoryTreeCondition;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductBelongCategoryTreeConditionTest extends TestCase
{
    public function testConditionCreation(): void
    {
        /** @var CategoryTreeId | MockObject $categoryTreeId */
        $categoryTreeId = $this->createMock(CategoryTreeId::class);
        $operator = 'belong';

        $condition = new ProductBelongCategoryTreeCondition([$categoryTreeId], $operator);

        $this->assertSame([$categoryTreeId], $condition->getTree());
        $this->assertSame($operator, $condition->getOperator());
        $this->assertSame(ProductBelongCategoryTreeCondition::TYPE, $condition->getType());
    }
}
