<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Grid\Tests\Column;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\TranslatableColumn;
use Ergonode\Grid\FilterInterface;
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
        $filter = $this->createMock(FilterInterface::class);
        $language = new Language(Language::PL);
        $domain = 'domain';
        $parameter = null;

        $column = new TranslatableColumn($field, $label, $language, $domain, $parameter, $filter);
        $this->assertSame($field, $column->getField());
        $this->assertSame($label, $column->getLabel());
        $this->assertSame($filter, $column->getFilter());
        $this->assertSame(TranslatableColumn::TYPE, $column->getType());
    }
}
