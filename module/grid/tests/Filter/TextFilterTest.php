<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Tests\Filter;

use Ergonode\Grid\Filter\TextFilter;
use PHPUnit\Framework\TestCase;

class TextFilterTest extends TestCase
{
    public function testRender(): void
    {
        $filter = new TextFilter();

        $result = $filter->render();

        $this->assertEquals([], $result);
    }

    public function testType(): void
    {
        $filter = new TextFilter();
        $this->assertEquals(TextFilter::TYPE, $filter->getType());
    }
}
