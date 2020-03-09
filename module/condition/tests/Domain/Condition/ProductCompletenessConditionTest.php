<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Tests\Domain\Condition;

use Ergonode\Condition\Domain\Condition\ProductCompletenessCondition;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductCompletenessConditionTest extends TestCase
{
    /**
     * @param string $completeness
     *
     * @dataProvider dataProvider
     */
    public function testConditionCreation(string $completeness): void
    {
        $condition = new ProductCompletenessCondition($completeness);
        $this->assertSame($completeness, $condition->getCompleteness());
        $this->assertSame('PRODUCT_COMPLETENESS_CONDITION', $condition->getType());
    }

    /**
     */
    public function testInvalidConditionCreation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $completeness = 'Incorrect data';

        new ProductCompletenessCondition($completeness);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return
            [
                [
                    'complete',
                ],
                [
                    'not complete',
                ],
            ];
    }
}
