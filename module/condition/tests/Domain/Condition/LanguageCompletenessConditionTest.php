<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Tests\Domain\Condition;

use Ergonode\Condition\Domain\Condition\LanguageCompletenessCondition;
use Ergonode\Core\Domain\ValueObject\Language;
use PHPUnit\Framework\TestCase;

/**
 */
class LanguageCompletenessConditionTest extends TestCase
{
    /**
     * @param string   $completeness
     * @param Language $language
     *
     * @dataProvider dataProvider
     */
    public function testConditionCreation(string $completeness, Language $language): void
    {
        $condition = new LanguageCompletenessCondition($completeness, $language);
        $this->assertSame($completeness, $condition->getCompleteness());
        $this->assertSame($language, $condition->getLanguage());
        $this->assertSame('LANGUAGE_COMPLETENESS_CONDITION', $condition->getType());
    }

    /**
     */
    public function testInvalidConditionCreation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $completeness = 'Incorrect data';
        $language = $this->createMock(Language::class);

        new LanguageCompletenessCondition($completeness, $language);
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
