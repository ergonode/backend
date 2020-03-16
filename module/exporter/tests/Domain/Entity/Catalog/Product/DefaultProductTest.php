<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Entity\Catalog\Product;

use Ergonode\Exporter\Domain\Entity\Catalog\AbstractExportAttributeValue;
use Ergonode\Exporter\Domain\Entity\Catalog\AttributeValue\DefaultExportAttributeValue;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 */
class DefaultProductTest extends TestCase
{
    /**
     * @var Uuid
     */
    private Uuid $id;

    /**
     * @var string
     */
    private string $sku;

    /**
     * @var Uuid[]
     */
    private array $category;

    /**
     * @var AbstractExportAttributeValue[]
     */
    private array $attribute;

    /**
     */
    protected function setUp(): void
    {
        $this->id = Uuid::uuid4();
        $this->sku = random_bytes(10);

        $this->category = [
            $this->createMock(Uuid::class),
        ];

        $this->attribute = [
            $this->createMock(AbstractExportAttributeValue::class),
            $this->createMock(DefaultExportAttributeValue::class),
        ];
    }

    /**
     */
    public function testConstructor():void
    {
        $product = new DefaultExportProduct(
            $this->id,
            $this->sku,
            $this->category,
            $this->attribute
        );

        $this->assertEquals($this->id, $product->getId());
        $this->assertEquals($this->sku, $product->getSku());
        $this->assertEquals($this->category, $product->getCategories());
        $this->assertEquals($this->attribute, $product->getAttributes());
    }
}
