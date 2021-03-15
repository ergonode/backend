<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Tests\Column;

use Ergonode\Grid\Column\LabelColumn;
use Ergonode\Grid\FilterInterface;
use PHPUnit\Framework\TestCase;

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
        $this->assertSame($field, $column->getField());
        $this->assertSame($label, $column->getLabel());
        $this->assertSame('LABEL', $column->getType());
    }
}
