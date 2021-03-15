<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Tests\Column;

use Ergonode\Grid\Column\ImageColumn;
use PHPUnit\Framework\TestCase;

class ImageColumnTest extends TestCase
{
    public function testGetters(): void
    {
        $field = 'Any id';

        $column = new ImageColumn($field);
        $this->assertSame($field, $column->getField());
        $this->assertNull($column->getLabel());
        $this->assertSame(ImageColumn::TYPE, $column->getType());
    }
}
