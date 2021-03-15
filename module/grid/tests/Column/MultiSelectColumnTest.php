<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Tests\Column;

use Ergonode\Grid\Column\MultiSelectColumn;
use Ergonode\Grid\FilterInterface;
use PHPUnit\Framework\TestCase;

class MultiSelectColumnTest extends TestCase
{
    public function testGetters(): void
    {
        $field = 'Any id';
        $label = 'Any label';
        /** @var FilterInterface $filter */
        $filter = $this->createMock(FilterInterface::class);

        $column = new MultiSelectColumn($field, $label, $filter);
        $this->assertSame($field, $column->getField());
        $this->assertSame($label, $column->getLabel());
        $this->assertSame($filter, $column->getFilter());
        $this->assertSame(MultiSelectColumn::TYPE, $column->getType());
    }
}
