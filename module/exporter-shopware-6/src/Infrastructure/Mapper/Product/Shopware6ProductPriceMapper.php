<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6CurrencyRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6TaxRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Shopware6ExporterMapperException;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6ProductMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductPrice;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Webmozart\Assert\Assert;

class Shopware6ProductPriceMapper implements Shopware6ProductMapperInterface
{
    private const PRECISION = 2;

    private AttributeRepositoryInterface $repository;

    private AttributeTranslationInheritanceCalculator $calculator;

    private Shopware6CurrencyRepositoryInterface $currencyRepository;

    private Shopware6TaxRepositoryInterface $taxRepository;

    public function __construct(
        AttributeRepositoryInterface $repository,
        AttributeTranslationInheritanceCalculator $calculator,
        Shopware6CurrencyRepositoryInterface $currencyRepository,
        Shopware6TaxRepositoryInterface $taxRepository
    ) {
        $this->repository = $repository;
        $this->calculator = $calculator;
        $this->currencyRepository = $currencyRepository;
        $this->taxRepository = $taxRepository;
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
        $tax = $this->tax($channel, $product);

        $shopware6Product->addPrice($this->getPrice($channel, $product));
        $shopware6Product->setTaxId($this->loadTaxId($channel, $tax));

        return $shopware6Product;
    }

    /**
     * @throws Shopware6ExporterMapperException
     */
    public function getPrice(
        Shopware6Channel $channel,
        AbstractProduct $product
    ): Shopware6ProductPrice {

        /** @var PriceAttribute $attribute */
        $attribute = $this->repository->load($channel->getAttributeProductPriceGross());
        $priceGross = $this->getPriceValue(
            $channel->getAttributeProductPriceGross(),
            $channel->getDefaultLanguage(),
            $product
        );
        $priceNet = $this->getPriceValue(
            $channel->getAttributeProductPriceNet(),
            $channel->getDefaultLanguage(),
            $product
        );

        return new Shopware6ProductPrice(
            $this->loadCurrencyId($channel, $attribute),
            round($priceNet, self::PRECISION),
            round($priceGross, self::PRECISION)
        );
    }

    /**
     * @throws Shopware6ExporterMapperException
     */
    public function tax(Shopware6Channel $channel, AbstractProduct $product): float
    {
        $attribute = $this->repository->load($channel->getAttributeProductTax());

        Assert::notNull($attribute);

        if (false === $product->hasAttribute($attribute->getCode())) {
            throw new Shopware6ExporterMapperException(
                sprintf('Attribute %s tax value not found %s ', $attribute->getCode()->getValue(), $product->getSku())
            );
        }
        $value = $product->getAttribute($attribute->getCode());

        return (float) $this->calculator->calculate($attribute, $value, $channel->getDefaultLanguage());
    }

    /**
     * @throws Shopware6ExporterMapperException
     */
    private function loadCurrencyId(Shopware6Channel $channel, PriceAttribute $attribute): string
    {
        $shopwareId = $this->currencyRepository->load($channel->getId(), $attribute->getCurrency()->getCode());

        if (is_null($shopwareId)) {
            throw new Shopware6ExporterMapperException(
                sprintf('No mapped currency %s ', $attribute->getCurrency()->getCode())
            );
        }

        return $shopwareId;
    }

    /**
     * @throws Shopware6ExporterMapperException
     */
    private function loadTaxId(Shopware6Channel $channel, float $tax): string
    {
        $shopwareId = $this->taxRepository->load($channel->getId(), $tax);

        if (is_null($shopwareId)) {
            throw new Shopware6ExporterMapperException(
                sprintf('No mapped tax value %s ', $tax)
            );
        }

        return $shopwareId;
    }

    /**
     * @param $product
     *
     *
     * @throws Shopware6ExporterMapperException
     */
    private function getPriceValue(AttributeId $productPrice, Language $defaultLanguage, $product): float
    {
        /** @var PriceAttribute $attribute */
        $attribute = $this->repository->load($productPrice);

        if (false === $product->hasAttribute($attribute->getCode())) {
            throw new Shopware6ExporterMapperException(
                sprintf('Attribute %s price not found ', $attribute->getCode()->getValue())
            );
        }

        $value = $product->getAttribute($attribute->getCode());
        $price = str_replace(
            ',',
            '.',
            $this->calculator->calculate($attribute, $value, $defaultLanguage)
        );

        if (!is_numeric($price)) {
            throw new Shopware6ExporterMapperException(
                sprintf('Attribute %s value "%s" is not valid price', $attribute->getCode()->getValue(), $price)
            );
        }

        return (float) $price;
    }
}
