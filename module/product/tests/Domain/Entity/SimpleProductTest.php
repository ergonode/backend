<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Entity;

use Ergonode\Product\Domain\Entity\SimpleProduct;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

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
     * @var TemplateId|MockObject
     */
    private TemplateId $templateId;

    protected function setUp(): void
    {
        $this->id = $this->createMock(ProductId::class);
        $this->sku = $this->createMock(Sku::class);
        $this->templateId = $this->createMock(TemplateId::class);
    }

    /**
     * @throws \Exception
     */
    public function testType(): void
    {
        $product = new SimpleProduct($this->id, $this->sku, $this->templateId);
        $this->assertSame(SimpleProduct::TYPE, $product->getType());
    }
}
