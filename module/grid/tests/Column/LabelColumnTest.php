<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Grid\Tests\Column;

use Ergonode\Grid\Column\LabelColumn;
use Ergonode\Grid\FilterInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class LabelColumnTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testGetters(): void
    {
        $field = 'any_id';
        $label = 'Any url';
        $filter = $this->createMock(FilterInterface::class);

        $column = new LabelColumn($field, $label, $filter);
        self::assertSame($field, $column->getField());
        self::assertSame($label, $column->getLabel());
        self::assertSame('LABEL', $column->getType());
    }
}
