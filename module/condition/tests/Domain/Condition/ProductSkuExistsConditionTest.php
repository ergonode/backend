<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Tests\Domain\Condition;

use PHPUnit\Framework\TestCase;
use Ergonode\Condition\Domain\Condition\ProductSkuExistsCondition;

class ProductSkuExistsConditionTest extends TestCase
{

    public function testConditionCreation(): void
    {
        $operator = 'someOperator';
        $value = 'someValue';
        $condition = new ProductSkuExistsCondition($operator, $value);

        $this->assertSame($operator, $condition->getOperator());
        $this->assertSame($value, $condition->getValue());
        $this->assertSame(ProductSkuExistsCondition::TYPE, $condition->getType());
    }
}
