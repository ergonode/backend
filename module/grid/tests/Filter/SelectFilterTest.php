<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Grid\Tests\Filter;

use Ergonode\Grid\Filter\SelectFilter;
use PHPUnit\Framework\TestCase;

/**
 */
class SelectFilterTest extends TestCase
{
    /**
     */
    public function testRender(): void
    {
        $configuration =
            ['options' =>
                [
                    'OPTION 1',
                    'OPTION 2',
                ],
            ];

        $filter = new SelectFilter($configuration['options']);

        $result = $filter->render();

        $this->assertEquals($configuration, $result);
    }

    /**
     */
    public function testType(): void
    {
        $filter = new SelectFilter([]);
        $this->assertEquals(SelectFilter::TYPE, $filter->getType());
    }
}
