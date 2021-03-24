<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Mapper\ProductToLongValueException;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\ProductMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Webmozart\Assert\Assert;

class ProductSEOMetaTitleMapper implements ProductMapperInterface
{
    private const MAX_LENGTH = 255;

    private AttributeRepositoryInterface $repository;

    private AttributeTranslationInheritanceCalculator $calculator;

    public function __construct(
        AttributeRepositoryInterface $repository,
        AttributeTranslationInheritanceCalculator $calculator
    ) {
        $this->repository = $repository;
        $this->calculator = $calculator;
    }

    /**
     * @throws ProductToLongValueException
     */
    public function map(
        Shopware6Channel $channel,
        Export $export,
        Shopware6Product $shopware6Product,
        AbstractProduct $product,
        ?Language $language = null
    ): Shopware6Product {

        if (null === $channel->getAttributeProductMetaTitle()) {
            return $shopware6Product;
        }
        $attribute = $this->repository->load($channel->getAttributeProductMetaTitle());

        Assert::notNull($attribute);

        if (false === $product->hasAttribute($attribute->getCode())) {
            return $shopware6Product;
        }

        $value = $this->calculator->calculate(
            $attribute,
            $product->getAttribute($attribute->getCode()),
            $language ?: $channel->getDefaultLanguage()
        );
        if ($value && mb_strlen($value) > self::MAX_LENGTH) {
            throw new ProductToLongValueException($attribute->getCode(), $product->getSku(), self::MAX_LENGTH);
        }

        $shopware6Product->setMetaTitle($value);

        return $shopware6Product;
    }
}
