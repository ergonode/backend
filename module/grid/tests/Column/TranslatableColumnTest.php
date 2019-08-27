<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Grid\Tests\Column;

use Ergonode\Grid\Column\TranslatableColumn;
use Ergonode\Grid\FilterInterface;
use PHPUnit\Framework\TestCase;
use Ergonode\Core\Domain\ValueObject\Language;

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

        $column = new TranslatableColumn($field, $label, $language, $filter);
        $this->assertSame($field, $column->getField());
        $this->assertSame($label, $column->getLabel());
        $this->assertSame($filter, $column->getFilter());
        $this->assertSame(TranslatableColumn::TYPE, $column->getType());
    }

    /**
     */
    public function testRender(): void
    {
        $field = 'Any id';
        $label = 'Any label';
        $PL = 'ANY RESULT';
        $array = [Language::PL => $PL];
        $record = [$field => json_encode($array)];
        $language = new Language(Language::PL);

        $column = new TranslatableColumn($field, $label, $language);
        $result = $column->render($field, $record);
        $this->assertSame($PL, $result);
    }
}
