<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Grid\Tests\Filter;

use Ergonode\Grid\Filter\MultiSelectFilter;
use PHPUnit\Framework\TestCase;
use Ergonode\Grid\Filter\Option\FilterOptionInterface;

/**
 */
class MultiSelectFilterTest extends TestCase
{
    /**
     */
    public function testRender(): void
    {
        $option = $this->createMock(FilterOptionInterface::class);
        $option->expects(self::once())->method('render');
        $option->expects(self::once())->method('getKey');

        $filter = new MultiSelectFilter([$option]);

        $filter->render();
    }

    /**
     */
    public function testType(): void
    {
        $filter = new MultiSelectFilter([]);
        self::assertEquals(MultiSelectFilter::TYPE, $filter->getType());
    }
}
