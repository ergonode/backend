<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Grid\Tests\Column;

use Ergonode\Grid\Column\BoolColumn;
use Ergonode\Grid\FilterInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class BoolColumnTest extends TestCase
{
    /**
     */
    public function testGetters(): void
    {
        $field = 'Any field';
        $label = 'Any label';
        $filter = $this->createMock(FilterInterface::class);

        $column = new BoolColumn($field, $label, $filter);
        self::assertSame($field, $column->getField());
        self::assertSame($label, $column->getLabel());
        self::assertSame($filter, $column->getFilter());
        self::assertSame(BoolColumn::TYPE, $column->getType());
    }
}
