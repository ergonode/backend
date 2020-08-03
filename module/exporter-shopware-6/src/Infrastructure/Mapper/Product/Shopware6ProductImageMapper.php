<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Entity\Attribute\AbstractImageAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6ProductMediaClient;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6ProductMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Webmozart\Assert\Assert;

/**
 */
class Shopware6ProductImageMapper implements Shopware6ProductMapperInterface
{
    private const SUPPORTED_TYPE = [
        AbstractImageAttribute::TYPE,
    ];
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @var AttributeTranslationInheritanceCalculator
     */
    private AttributeTranslationInheritanceCalculator $calculator;

    /**
     * @var MultimediaRepositoryInterface
     */
    private MultimediaRepositoryInterface $multimediaRepository;

    /**
     * @var Shopware6ProductMediaClient
     */
    private Shopware6ProductMediaClient $mediaClient;

    /**
     * @param AttributeRepositoryInterface              $repository
     * @param AttributeTranslationInheritanceCalculator $calculator
     * @param MultimediaRepositoryInterface             $multimediaRepository
     * @param Shopware6ProductMediaClient               $mediaClient
     */
    public function __construct(
        AttributeRepositoryInterface $repository,
        AttributeTranslationInheritanceCalculator $calculator,
        MultimediaRepositoryInterface $multimediaRepository,
        Shopware6ProductMediaClient $mediaClient
    ) {
        $this->repository = $repository;
        $this->calculator = $calculator;
        $this->multimediaRepository = $multimediaRepository;
        $this->mediaClient = $mediaClient;
    }

    /**
     * @param Shopware6Product $shopware6Product
     * @param AbstractProduct  $product
     * @param Shopware6Channel $channel
     *
     * @return Shopware6Product
     */
    public function map(
        Shopware6Product $shopware6Product,
        AbstractProduct $product,
        Shopware6Channel $channel
    ): Shopware6Product {

        foreach ($channel->getPropertyGroup() as $attributeId) {
            $this->attributeMap($shopware6Product, $attributeId, $product, $channel);
        }

        return $shopware6Product;
    }

    /**
     * @param Shopware6Product $shopware6Product
     * @param AttributeId      $attributeId
     * @param AbstractProduct  $product
     * @param Shopware6Channel $channel
     *
     * @return Shopware6Product
     */
    private function attributeMap(
        Shopware6Product $shopware6Product,
        AttributeId $attributeId,
        AbstractProduct $product,
        Shopware6Channel $channel
    ): Shopware6Product {
        $attribute = $this->repository->load($attributeId);
        Assert::notNull($attribute);

        if (in_array($attribute->getType(), self::SUPPORTED_TYPE, true)) {
            if (false === $product->hasAttribute($attribute->getCode())) {
                return $shopware6Product;
            }
            $value = $product->getAttribute($attribute->getCode());
            $calculateValue = $this->calculator->calculate($attribute, $value, $channel->getDefaultLanguage());
            if ($calculateValue) {
                $multimediaId = new MultimediaId($calculateValue);
                $this->getShopware6MultimediaId($multimediaId, $shopware6Product, $channel);
            }
        }


        return $shopware6Product;
    }

    /**
     * @param MultimediaId     $multimediaId
     * @param Shopware6Product $shopware6Product
     * @param Shopware6Channel $channel
     *
     * @return Shopware6Product
     */
    private function getShopware6MultimediaId(
        MultimediaId $multimediaId,
        Shopware6Product $shopware6Product,
        Shopware6Channel $channel
    ): Shopware6Product {
        $multimedia = $this->multimediaRepository->load($multimediaId);
        if ($multimedia) {
            $shopwareId = $this->mediaClient->findOrCreateMedia($channel, $multimedia);
            if ($shopwareId) {
                $shopware6Product->addMedia($shopwareId);
            }
        }

        return $shopware6Product;
    }
}
