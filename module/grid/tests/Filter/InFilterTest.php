<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Tests\Filter;

use Ergonode\Grid\Filter\InFilter;
use PHPUnit\Framework\TestCase;

class InFilterTest extends TestCase
{
    public function testRender(): void
    {
        $filter = new InFilter();

        $result = $filter->render();

        self::assertEquals([], $result);
    }

    public function testType(): void
    {
        $filter = new InFilter();
        self::assertEquals(InFilter::TYPE, $filter->getType());
    }
}
