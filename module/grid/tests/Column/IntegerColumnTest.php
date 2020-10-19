<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Grid\Tests\Column;

use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\FilterInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class IntegerColumnTest extends TestCase
{
    /**
     */
    public function testGetters(): void
    {
        $field = 'Any id';
        $label = 'Any label';
        $filter = $this->createMock(FilterInterface::class);

        $column = new IntegerColumn($field, $label, $filter);
        self::assertSame($field, $column->getField());
        self::assertSame($label, $column->getLabel());
        self::assertSame($filter, $column->getFilter());
        self::assertSame(IntegerColumn::TYPE, $column->getType());
    }
}
