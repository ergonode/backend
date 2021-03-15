<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Tests\Filter;

use Ergonode\Grid\Filter\MultiSelectFilter;
use PHPUnit\Framework\TestCase;
use Ergonode\Grid\Filter\Option\FilterOptionInterface;

class MultiSelectFilterTest extends TestCase
{
    public function testRender(): void
    {
        $option = $this->createMock(FilterOptionInterface::class);
        $option->expects($this->once())->method('render');
        $option->expects($this->once())->method('getKey');

        $filter = new MultiSelectFilter([$option]);

        $filter->render();
    }

    public function testType(): void
    {
        $filter = new MultiSelectFilter([]);
        $this->assertEquals(MultiSelectFilter::TYPE, $filter->getType());
    }
}
