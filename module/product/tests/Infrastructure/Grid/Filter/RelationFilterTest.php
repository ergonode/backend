<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Grid\Filter;

use Ergonode\Product\Infrastructure\Grid\Filter\RelationFilter;
use PHPUnit\Framework\TestCase;

class RelationFilterTest extends TestCase
{
    public function testRender(): void
    {
        $filter = new RelationFilter();

        $result = $filter->render();

        $this->assertEquals([], $result);
    }

    public function testType(): void
    {
        $filter = new RelationFilter();
        $this->assertEquals(RelationFilter::TYPE, $filter->getType());
    }
}
