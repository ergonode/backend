<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6CurrencyRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6TaxRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Shopware6ExporterMapperException;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6ProductMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\CreateShopware6Product;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Webmozart\Assert\Assert;

/**
 */
class Shopware6ProductPriceMapper implements Shopware6ProductMapperInterface
{
    private const PRECISION = 2;

    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @var AttributeTranslationInheritanceCalculator
     */
    private AttributeTranslationInheritanceCalculator $calculator;

    /**
     * @var Shopware6CurrencyRepositoryInterface
     */
    private Shopware6CurrencyRepositoryInterface $currencyRepository;

    /**
     * @var Shopware6TaxRepositoryInterface
     */
    private Shopware6TaxRepositoryInterface $taxRepository;

    /**
     * @param AttributeRepositoryInterface              $repository
     * @param AttributeTranslationInheritanceCalculator $calculator
     * @param Shopware6CurrencyRepositoryInterface      $currencyRepository
     * @param Shopware6TaxRepositoryInterface           $taxRepository
     */
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
     * @param Shopware6Product|CreateShopware6Product $shopware6Product
     * @param AbstractProduct                         $product
     * @param Shopware6Channel                        $channel
     *
     * @return Shopware6Product
     *
     * @throws Shopware6ExporterMapperException
     */
    public function map(
        Shopware6Product $shopware6Product,
        AbstractProduct $product,
        Shopware6Channel $channel
    ): Shopware6Product {

        if ($shopware6Product instanceof CreateShopware6Product) {
            $tax = $this->tax($channel, $product);

            $shopware6Product->addPrice($this->price($channel, $product, $tax));
            $shopware6Product->setTaxId($this->loadTaxId($channel, $tax));
        }

        return $shopware6Product;
    }

    /**
     * @param Shopware6Channel $channel
     * @param AbstractProduct  $product
     * @param float            $tax
     *
     * @return array
     *
     * @throws Shopware6ExporterMapperException
     */
    public function price(
        Shopware6Channel $channel,
        AbstractProduct $product,
        float $tax
    ): array {
        /** @var PriceAttribute $attribute */
        $attribute = $this->repository->load($channel->getProductPrice());

        if (false === $product->hasAttribute($attribute->getCode())) {
            throw new Shopware6ExporterMapperException(
                sprintf('Attribute %s price not found ', $attribute->getCode()->getValue())
            );
        }

        $value = $product->getAttribute($attribute->getCode());
        $price = str_replace(
            ',',
            '.',
            $this->calculator->calculate($attribute, $value, $channel->getDefaultLanguage())
        );

        if (!is_numeric($price)) {
            throw new Shopware6ExporterMapperException(
                sprintf('Attribute %s value "%s" is not valid price', $attribute->getCode()->getValue(), $price)
            );
        }
        $priceGross = (float) $price;
        $priceNet = $priceGross / ($tax / 100 + 1);

        return [
            'currencyId' => $this->loadCurrencyId($channel, $attribute),
            'net' => round($priceNet, self::PRECISION),
            'linked' => false,
            'gross' => round($priceGross, self::PRECISION),
        ];
    }

    /**
     * @param Shopware6Channel $channel
     * @param AbstractProduct  $product
     *
     * @return float
     *
     * @throws Shopware6ExporterMapperException
     */
    public function tax(Shopware6Channel $channel, AbstractProduct $product): float
    {
        $attribute = $this->repository->load($channel->getProductTax());

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
     * @param Shopware6Channel $channel
     * @param PriceAttribute   $attribute
     *
     * @return string
     *
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
     * @param Shopware6Channel $channel
     * @param float            $tax
     *
     * @return string
     *
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
}
