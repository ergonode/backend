<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Factory\Decorator;

use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\Attribute\CreatedAtSystemAttribute;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Product\Domain\Factory\ProductFactoryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Value\Domain\ValueObject\StringValue;

/**
 */
class CreatedAtAttributeDecorator implements ProductFactoryInterface
{
    /**
     * @var ProductFactoryInterface
     */
    private $factory;

    /**
     * @param ProductFactoryInterface $factory
     */
    public function __construct(ProductFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function isSupportedBy(string $type): bool
    {
        return $this->factory->isSupportedBy($type);
    }

    /**
     * @param ProductId  $id
     * @param Sku        $sku
     * @param TemplateId $templateId
     * @param array      $categories
     * @param array      $attributes
     *
     * @return AbstractProduct
     *
     * @throws \Exception
     */
    public function create(
        ProductId $id,
        Sku $sku,
        TemplateId $templateId,
        array $categories = [],
        array $attributes = []
    ): AbstractProduct {
        $createdAt = new \DateTime();
        $attributes[CreatedAtSystemAttribute::CODE] = new StringValue($createdAt->format('Y-m-d H:i:s'));

        return $this->factory->create($id, $sku, $templateId, $categories, $attributes);
    }
}
