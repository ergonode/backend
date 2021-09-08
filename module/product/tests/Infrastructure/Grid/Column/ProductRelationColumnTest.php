<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Grid\Column;

use Ergonode\Product\Infrastructure\Grid\Column\ProductRelationColumn;
use PHPUnit\Framework\TestCase;
use Ergonode\Product\Infrastructure\Grid\Filter\RelationFilter;

class ProductRelationColumnTest extends TestCase
{
    /**
     * @dataProvider data
     */
    public function testCreate(string $field, ?string $label = null): void
    {
        $column = new ProductRelationColumn($field, $label);
        self::assertSame($field, $column->getField());
        self::assertSame($label, $column->getLabel());
        self::assertSame(ProductRelationColumn::TYPE, $column->getType());
        self::assertInstanceOf(RelationFilter::class, $column->getFilter());
    }

    public function data(): array
    {
        return [
            [
                'Field 1',
                'Label 1',
            ],
            [
                'Field 2',
                null,
            ],
        ];
    }
}
