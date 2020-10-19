<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Grid\Tests\Column;

use Ergonode\Grid\Column\CheckColumn;
use Ergonode\Grid\FilterInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class CheckColumnTest extends TestCase
{
    /**
     */
    public function testGetters(): void
    {
        $field = 'Any id';
        $label = 'Any label';
        $filter = $this->createMock(FilterInterface::class);

        $column = new CheckColumn($field, $label, $filter);
        self::assertSame($field, $column->getField());
        self::assertSame($label, $column->getLabel());
        self::assertSame($filter, $column->getFilter());
        self::assertSame(CheckColumn::TYPE, $column->getType());
    }
}
