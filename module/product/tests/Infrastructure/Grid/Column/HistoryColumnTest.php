<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Grid\Column;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Infrastructure\Grid\Column\HistoryColumn;
use PHPUnit\Framework\TestCase;

class HistoryColumnTest extends TestCase
{

    public function testGetters(): void
    {
        $field = 'Any field';
        $parameter = 'Any parameter';
        $label = 'Any label';
        $language = $this->createMock(Language::class);
        $column = new HistoryColumn($field, $parameter, $label, $language);
        $this->assertSame($field, $column->getField());
        $this->assertSame($parameter, $column->getParameterField());
        $this->assertSame($label, $column->getLabel());
        $this->assertSame($language, $column->getLanguage());
        $this->assertSame(HistoryColumn::TYPE, $column->getType());
    }
}
