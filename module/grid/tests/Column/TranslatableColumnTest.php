<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Grid\Tests\Column;

use Ergonode\Core\Domain\ValueObject\Language;
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
        $language = new Language(Language::PL);

        $column = new TranslatableColumn($field, $label, $language, $parameters, $domain);
        $this->assertSame($field, $column->getField());
        $this->assertSame($label, $column->getLabel());
        $this->assertSame($parameters, $column->getParameters());
        $this->assertSame($domain, $column->getDomain());
        $this->assertSame(TranslatableColumn::TYPE, $column->getType());
    }
}
