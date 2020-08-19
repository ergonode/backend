<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\PropertyGroup;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractTextareaAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\AbstractShopware6ProductPropertyGroupMapper;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;

/**
 */
class Shopware6ProductGroupTextAreaMapper extends AbstractShopware6ProductPropertyGroupMapper
{
    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return AbstractTextareaAttribute::TYPE;
    }

    /**
     * {@inheritDoc}
     */
    protected function addProperty(
        Shopware6Product $shopware6Product,
        AbstractAttribute $attribute,
        AbstractProduct $product,
        Shopware6Channel $channel,
        ?Language $language = null
    ): Shopware6Product {
        $propertyGroupOptions = $this->createPropertyGroupOptions(
            $channel,
            $attribute,
            $product->getAttribute($attribute->getCode())
        );

        $propertyId = $this->propertyGroupOptionClient->findByNameOrCreate(
            $channel,
            $attribute->getId(),
            $propertyGroupOptions
        );
        if ($propertyId) {
            $shopware6Product->addProperty($propertyId);
        }

        return $shopware6Product;
    }
}
