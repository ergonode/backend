<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Grid\Tests\Column;

use Ergonode\Grid\Column\ActionColumn;
use PHPUnit\Framework\TestCase;

/**
 */
class ActionColumnTest extends TestCase
{
    /**
     */
    public function testGetters(): void
    {
        $field = 'Any id';
        $label = 'Any label';

        $column = new ActionColumn($field, $label);
        $this->assertSame($field, $column->getField());
        $this->assertSame($label, $column->getLabel());
        $this->assertSame(ActionColumn::TYPE, $column->getType());
    }

    /**
     */
    public function testRender(): void
    {
        $field = 'Any id';
        $label = 'Any label';
        $record = [];

        $column = new ActionColumn($field, $label);
        $result = $column->render($field, $record);
        $this->assertTrue($result);
    }
}
