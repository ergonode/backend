<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Grid\Tests\Column;

use Ergonode\Grid\Column\ImageColumn;
use PHPUnit\Framework\TestCase;

/**
 */
class ImageColumnTest extends TestCase
{
    /**
     */
    public function testGetters(): void
    {
        $field = 'Any id';

        $column = new ImageColumn($field);
        self::assertSame($field, $column->getField());
        self::assertNull($column->getLabel());
        self::assertSame(ImageColumn::TYPE, $column->getType());
    }
}
