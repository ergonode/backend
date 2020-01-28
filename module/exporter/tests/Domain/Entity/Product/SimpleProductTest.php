<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Entity\Product;

use Ergonode\Exporter\Domain\Entity\AbstractExportAttributeValue;
use Ergonode\Exporter\Domain\Entity\AttributeValue\DefaultExportAttributeValue;
use Ergonode\Exporter\Domain\Entity\ExportCategoryCode;
use PHPUnit\Framework\TestCase;

/**
 */
class SimpleProductTest extends TestCase
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $sku;

    /**
     * @var ExportCategoryCode[]
     */
    private array $category;

    /**
     * @var AbstractExportAttributeValue[]
     */
    private array $attribute;

    /**
     */
    protected function setUp()
    {
        $this->id = random_bytes(10);
        $this->sku = random_bytes(10);

        $this->category = [
            $this->createMock(ExportCategoryCode::class),
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
        $product = new SimpleExportProduct(
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
