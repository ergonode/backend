<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Grid\Column\Renderer;

use PHPUnit\Framework\TestCase;
use Ergonode\Product\Infrastructure\Grid\Column\Renderer\ProductRelationColumnRenderer;
use Ergonode\Grid\Column\AbstractColumn;
use Ergonode\Product\Infrastructure\Grid\Column\ProductRelationColumn;

class ProductRelationColumnRendererTest extends TestCase
{
    public function testIsSupported(): void
    {
        $renderer = new ProductRelationColumnRenderer();
        self::assertFalse($renderer->supports($this->createMock(AbstractColumn::class)));
        self::assertTrue($renderer->supports($this->createMock(ProductRelationColumn::class)));
    }

    /**
     * @param mixed $result
     *
     * @dataProvider data
     */
    public function testRender(array $row, $result): void
    {
        $renderer = new ProductRelationColumnRenderer();
        $data = $renderer->render($this->createMock(ProductRelationColumn::class), 'id', $row);
        self::assertSame($result, $data);
    }

    public function data(): array
    {
        return [
            [
                ['id' => 'data'],
                ['data'],
            ],
            [
                ['id' => '["data1","data2"]'],
                ['data1', 'data2'],
            ],
            [
                ['id' => null],
                null,
            ],
            [
                ['id' => []],
                [],
            ],
        ];
    }
}
