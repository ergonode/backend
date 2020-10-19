<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Grid\Tests\Column;

use Ergonode\Grid\Column\TranslatableColumn;
use PHPUnit\Framework\TestCase;

/**
 */
class TranslatableColumnTest extends TestCase
{
    /**
     */
    public function testGetters(): void
    {
        $field = 'Any id';
        $label = 'Any label';
        $parameters = 'Any parameters field';
        $domain = 'Any domain';

        $column = new TranslatableColumn($field, $label, $parameters, $domain);
        self::assertSame($field, $column->getField());
        self::assertSame($label, $column->getLabel());
        self::assertSame($parameters, $column->getParameters());
        self::assertSame($domain, $column->getDomain());
        self::assertSame(TranslatableColumn::TYPE, $column->getType());
    }
}
