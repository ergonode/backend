<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Grid\Tests\Column;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\TranslatableColumn;
use Ergonode\Grid\FilterInterface;
use PHPUnit\Framework\MockObject\MockObject;
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
        /** @var FilterInterface|MockObject $filter */
        $filter = $this->createMock(FilterInterface::class);
        $language = new Language(Language::PL);

        $column = new TranslatableColumn($field, $label, $language, $filter);
        $this->assertSame($field, $column->getField());
        $this->assertSame($label, $column->getLabel());
        $this->assertSame($filter, $column->getFilter());
        $this->assertSame(TranslatableColumn::TYPE, $column->getType());
    }
}
