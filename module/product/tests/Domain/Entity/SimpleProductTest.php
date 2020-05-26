<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Domain\Entity;

use Ergonode\Product\Domain\Entity\SimpleProduct;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\Product\Domain\ValueObject\Sku;

/**
 */
class SimpleProductTest extends TestCase
{
    /**
     * @var ProductId|MockObject
     */
    private ProductId $id;

    /**
     * @var Sku|MockObject
     */
    private Sku $sku;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(ProductId::class);
        $this->sku = $this->createMock(Sku::class);
    }

    /**
     * @throws \Exception
     */
    public function testType(): void
    {
        $product = new SimpleProduct($this->id, $this->sku);
        $this->assertSame(SimpleProduct::TYPE, $product->getType());
    }
}
