<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Grid\Tests\Column;

use Ergonode\Grid\Column\LabelColumn;
use Ergonode\Grid\FilterInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class LabelColumnTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testGetters(): void
    {
        $field = 'any_id';
        $label = 'Any url';
        $statuses = [];
        $filter = $this->createMock(FilterInterface::class);

        $column = new LabelColumn($field, $label, $statuses, $filter);
        $this->assertSame($field, $column->getField());
        $this->assertSame($label, $column->getLabel());
        $this->assertSame('LABEL', $column->getType());
    }
}
