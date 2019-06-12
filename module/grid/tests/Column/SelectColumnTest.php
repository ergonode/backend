<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Grid\Tests\Column;

use Ergonode\Grid\Column\MultiSelectColumn;
use Ergonode\Grid\FilterInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class SelectColumnTest extends TestCase
{
    /**
     */
    public function testGetters(): void
    {
        $field = 'Any id';
        $label = 'Any label';
        $filter = $this->createMock(FilterInterface::class);

        $column = new MultiSelectColumn($field, $label, $filter);
        $this->assertSame($field, $column->getField());
        $this->assertSame($label, $column->getLabel());
        $this->assertSame($filter, $column->getFilter());
        $this->assertSame(MultiSelectColumn::TYPE, $column->getType());
    }

    /**
     */
    public function testRender(): void
    {
        $field = 'Any id';
        $label = 'Any label';
        $array = ['Any result'];
        $record = [$field => json_encode($array)];

        $column = new MultiSelectColumn($field, $label);
        $result = $column->render($field, $record);
        $this->assertSame($array, $result);
    }
}
