<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Product\Infrastructure\Grid\Column;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Infrastructure\Grid\Column\HistoryColumn;
use PHPUnit\Framework\TestCase;

/**
 */
class HistoryColumnTest extends TestCase
{

    /**
     */
    public function testGetters(): void
    {
        $field = 'Any field';
        $parameter = 'Any parameter';
        $label = 'Any label';
        $language = $this->createMock(Language::class);
        $column = new HistoryColumn($field, $parameter, $label, $language);
        self::assertSame($field, $column->getField());
        self::assertSame($parameter, $column->getParameterField());
        self::assertSame($label, $column->getLabel());
        self::assertSame($language, $column->getLanguage());
        self::assertSame(HistoryColumn::TYPE, $column->getType());
    }
}
