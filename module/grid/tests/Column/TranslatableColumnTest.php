<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Tests\Column;

use Ergonode\Grid\Column\TranslatableColumn;
use PHPUnit\Framework\TestCase;

class TranslatableColumnTest extends TestCase
{
    public function testGetters(): void
    {
        $field = 'Any id';
        $label = 'Any label';
        $parameters = 'Any parameters field';
        $domain = 'Any domain';

        $column = new TranslatableColumn($field, $label, $parameters, $domain);
        $this->assertSame($field, $column->getField());
        $this->assertSame($label, $column->getLabel());
        $this->assertSame($parameters, $column->getParameters());
        $this->assertSame($domain, $column->getDomain());
        $this->assertSame(TranslatableColumn::TYPE, $column->getType());
    }
}
