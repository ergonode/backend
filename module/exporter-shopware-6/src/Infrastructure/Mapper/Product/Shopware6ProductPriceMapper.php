<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Domain\Repository\Shopwer6CurrencyRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopwer6TaxRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Shopware6ExporterMapperException;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6ProductMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\CreateShopwareProduct;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;

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
     * @var Shopwer6CurrencyRepositoryInterface
     */
    private Shopwer6CurrencyRepositoryInterface $currencyRepository;

    /**
     * @var Shopwer6TaxRepositoryInterface
     */
    private Shopwer6TaxRepositoryInterface $taxRepository;

    /**
     * @param AttributeRepositoryInterface              $repository
     * @param AttributeTranslationInheritanceCalculator $calculator
     * @param Shopwer6CurrencyRepositoryInterface       $currencyRepository
     * @param Shopwer6TaxRepositoryInterface            $taxRepository
     */
    public function __construct(
        AttributeRepositoryInterface $repository,
        AttributeTranslationInheritanceCalculator $calculator,
        Shopwer6CurrencyRepositoryInterface $currencyRepository,
        Shopwer6TaxRepositoryInterface $taxRepository
    ) {
        $this->repository = $repository;
        $this->calculator = $calculator;
        $this->currencyRepository = $currencyRepository;
        $this->taxRepository = $taxRepository;
    }


    /**
     * @param Shopware6Product|CreateShopwareProduct $shopware6Product
     * @param AbstractProduct                        $product
     * @param Shopware6ExportApiProfile              $profile
     *
     * @return Shopware6Product
     *
     * @throws Shopware6ExporterMapperException
     */
    public function map(
        Shopware6Product $shopware6Product,
        AbstractProduct $product,
        Shopware6ExportApiProfile $profile
    ): Shopware6Product {

        if ($shopware6Product instanceof CreateShopwareProduct) {
            $tax = $this->tax($profile, $product);

            $shopware6Product->addPrice($this->price($profile, $product, $tax));
            $shopware6Product->setTaxId($this->loadTaxId($profile, $tax));
        }

        return $shopware6Product;
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     * @param AbstractProduct           $product
     * @param float                     $tax
     *
     * @return array
     *
     * @throws Shopware6ExporterMapperException
     */
    public function price(
        Shopware6ExportApiProfile $profile,
        AbstractProduct $product,
        float $tax
    ): array {
        /** @var PriceAttribute $attribute */
        $attribute = $this->repository->load($profile->getProductPrice());

        if (false === $product->hasAttribute($attribute->getCode())) {
            throw new Shopware6ExporterMapperException(
                sprintf('Attribute %s price not found ', $attribute->getCode()->getValue())
            );
        }

        $value = $product->getAttribute($attribute->getCode());
        $price = str_replace(
            ',',
            '.',
            $this->calculator->calculate($attribute, $value, $profile->getDefaultLanguage())
        );

        if (!is_numeric($price)) {
            throw new Shopware6ExporterMapperException(
                sprintf('Attribute %s value "%s" is not valid price', $attribute->getCode()->getValue(), $price)
            );
        }
        $priceGross = (float) $price;
        $priceNet = $priceGross / ($tax / 100 + 1);

        return [
            'currencyId' => $this->loadCurrencyId($profile, $attribute),
            'net' => round($priceNet, self::PRECISION),
            'linked' => false,
            'gross' => round($priceGross, self::PRECISION),
        ];
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     * @param AbstractProduct           $product
     *
     * @return float
     *
     * @throws Shopware6ExporterMapperException
     */
    public function tax(Shopware6ExportApiProfile $profile, AbstractProduct $product): float
    {
        $attribute = $this->repository->load($profile->getProductTax());
        if (false === $product->hasAttribute($attribute->getCode())) {
            throw new Shopware6ExporterMapperException(
                sprintf('Attribute %s tax not found ', $attribute->getCode()->getValue())
            );
        }
        $value = $product->getAttribute($attribute->getCode());

        return floatval($this->calculator->calculate($attribute, $value, $profile->getDefaultLanguage()));
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     * @param PriceAttribute            $attribute
     *
     * @return string
     *
     * @throws Shopware6ExporterMapperException
     */
    private function loadCurrencyId(Shopware6ExportApiProfile $profile, PriceAttribute $attribute): string
    {
        $shopwareId = $this->currencyRepository->load($profile->getId(), $attribute->getCurrency()->getCode());

        if (is_null($shopwareId)) {
            throw new Shopware6ExporterMapperException(
                sprintf('No mapped currency %s ', $attribute->getCurrency()->getCode())
            );
        }

        return $shopwareId;
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     * @param float                     $tax
     *
     * @return string
     *
     * @throws Shopware6ExporterMapperException
     */
    private function loadTaxId(Shopware6ExportApiProfile $profile, float $tax): string
    {
        $shopwareId = $this->taxRepository->load($profile->getId(), $tax);

        if (is_null($shopwareId)) {
            throw new Shopware6ExporterMapperException(
                sprintf('No mapped tax value %s ', $tax)
            );
        }

        return $shopwareId;
    }
}
