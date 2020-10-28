<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Tests\Column;

use Ergonode\Grid\Column\BoolColumn;
use Ergonode\Grid\FilterInterface;
use PHPUnit\Framework\TestCase;

class BoolColumnTest extends TestCase
{
    public function testGetters(): void
    {
        $field = 'Any field';
        $label = 'Any label';
        $filter = $this->createMock(FilterInterface::class);

        $column = new BoolColumn($field, $label, $filter);
        $this->assertSame($field, $column->getField());
        $this->assertSame($label, $column->getLabel());
        $this->assertSame($filter, $column->getFilter());
        $this->assertSame(BoolColumn::TYPE, $column->getType());
    }
}
