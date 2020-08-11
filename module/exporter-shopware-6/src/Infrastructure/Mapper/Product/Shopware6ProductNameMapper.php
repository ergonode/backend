<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Shopware6ExporterMapperException;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6ProductMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Webmozart\Assert\Assert;

/**
 */
class Shopware6ProductNameMapper implements Shopware6ProductMapperInterface
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @var AttributeTranslationInheritanceCalculator
     */
    private AttributeTranslationInheritanceCalculator $calculator;

    /**
     * @param AttributeRepositoryInterface              $repository
     * @param AttributeTranslationInheritanceCalculator $calculator
     */
    public function __construct(
        AttributeRepositoryInterface $repository,
        AttributeTranslationInheritanceCalculator $calculator
    ) {
        $this->repository = $repository;
        $this->calculator = $calculator;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Shopware6ExporterMapperException
     */
    public function map(
        Shopware6Product $shopware6Product,
        AbstractProduct $product,
        Shopware6Channel $channel,
        ?Language $language = null
    ): Shopware6Product {

        $attribute = $this->repository->load($channel->getProductName());
        Assert::notNull($attribute);

        if (false === $product->hasAttribute($attribute->getCode())) {
            throw new Shopware6ExporterMapperException(
                sprintf('Attribute %s value not found %s', $attribute->getCode()->getValue(), $product->getSku())
            );
        }

        $value = $product->getAttribute($attribute->getCode());
        $name = $this->calculator->calculate($attribute, $value, $language ?: $channel->getDefaultLanguage());
        $shopware6Product->setName($name);

        return $shopware6Product;
    }
}
