<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Tests\Column;

use Ergonode\Grid\Column\CheckColumn;
use Ergonode\Grid\FilterInterface;
use PHPUnit\Framework\TestCase;

class CheckColumnTest extends TestCase
{
    public function testGetters(): void
    {
        $field = 'Any id';
        $label = 'Any label';
        $filter = $this->createMock(FilterInterface::class);

        $column = new CheckColumn($field, $label, $filter);
        $this->assertSame($field, $column->getField());
        $this->assertSame($label, $column->getLabel());
        $this->assertSame($filter, $column->getFilter());
        $this->assertSame(CheckColumn::TYPE, $column->getType());
    }
}
