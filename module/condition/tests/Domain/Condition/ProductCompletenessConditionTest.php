<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Tests\Domain\Condition;

use Ergonode\Condition\Domain\Condition\ProductCompletenessCondition;
use Ergonode\Core\Domain\ValueObject\Language;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductCompletenessConditionTest extends TestCase
{
    /**
     * @param string   $completeness
     * @param Language $language
     *
     * @dataProvider dataProvider
     */
    public function testConditionCreation(string $completeness, Language $language): void
    {
        $condition = new ProductCompletenessCondition($completeness, $language);
        $this->assertSame($completeness, $condition->getCompleteness());
        $this->assertSame($language, $condition->getLanguage());
        $this->assertSame('PRODUCT_COMPLETENESS_CONDITION', $condition->getType());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidConditionCreation(): void
    {
        $completeness = 'Incorrect data';
        $language = $this->createMock(Language::class);

        new ProductCompletenessCondition($completeness, $language);
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
                    $this->createMock(Language::class),
                ],
                [
                    'not complete',
                    $this->createMock(Language::class),
                ],
            ];
    }
}
