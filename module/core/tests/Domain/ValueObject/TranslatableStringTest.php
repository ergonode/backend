<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Domain\ValueObject;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;

class TranslatableStringTest extends TestCase
{
    /**
     * @dataProvider hasValuesProvider
     */
    public function testShouldHaveTranslation(TranslatableString $string, string $language, bool $has): void
    {
        $this->assertEquals(
            $string->has(new Language($language)),
            $has,
        );
    }

    public function hasValuesProvider(): array
    {
        return [
            [new TranslatableString(['en_EN' => 'english']), 'en_EN', true],
            [new TranslatableString(['en_EN' => null]), 'en_EN', true],
            [new TranslatableString(['en_EN' => null]), 'en_GB', false],
            [new TranslatableString(['en_EN' => 'english']), 'en_GB', false],
        ];
    }
}
