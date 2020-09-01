<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Process;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6LanguageRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Builder\Shopware6ProductBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6ProductClient;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Webmozart\Assert\Assert;

/**
 */
class ProductShopware6ExportProcess
{
    /**
     * @var Shopware6ProductBuilder
     */
    private Shopware6ProductBuilder $builder;

    /**
     * @var Shopware6ProductClient
     */
    private Shopware6ProductClient $productClient;

    /**
     * @var Shopware6LanguageRepositoryInterface
     */
    private Shopware6LanguageRepositoryInterface  $languageRepository;

    /**
     * @param Shopware6ProductBuilder              $builder
     * @param Shopware6ProductClient               $productClient
     * @param Shopware6LanguageRepositoryInterface $languageRepository
     */
    public function __construct(
        Shopware6ProductBuilder $builder,
        Shopware6ProductClient $productClient,
        Shopware6LanguageRepositoryInterface $languageRepository
    ) {
        $this->builder = $builder;
        $this->productClient = $productClient;
        $this->languageRepository = $languageRepository;
    }

    /**
     * @param ExportId         $id
     * @param Shopware6Channel $channel
     * @param AbstractProduct  $product
     *
     * @throws \Exception
     */
    public function process(ExportId $id, Shopware6Channel $channel, AbstractProduct $product): void
    {
        $shopwareProduct = $this->productClient->find($channel, $product);

        if ($shopwareProduct) {
            $this->updateProduct($channel, $shopwareProduct, $product);
        } else {
            $shopwareProduct = new Shopware6Product();
            $this->builder->build($shopwareProduct, $product, $channel);
            $this->productClient->insert($channel, $shopwareProduct, $product->getId());
        }

        foreach ($channel->getLanguages() as $language) {
            if ($this->languageRepository->exists($channel->getId(), $language->getCode())) {
                $this->updateProductWithLanguage($channel, $language, $product);
            }
        }
    }

    /**
     * @param Shopware6Channel       $channel
     * @param Shopware6Product       $shopwareProduct
     * @param AbstractProduct        $product
     * @param Language|null          $language
     * @param Shopware6Language|null $shopwareLanguage
     */
    private function updateProduct(
        Shopware6Channel $channel,
        Shopware6Product $shopwareProduct,
        AbstractProduct $product,
        ?Language $language = null,
        ?Shopware6Language $shopwareLanguage = null
    ): void {
        $this->builder->build($shopwareProduct, $product, $channel, $language);
        if ($shopwareProduct->isModified()) {
            $this->productClient->update($channel, $shopwareProduct, $shopwareLanguage);
        }
    }

    /**
     * @param Shopware6Channel $channel
     * @param Language         $language
     * @param AbstractProduct  $product
     *
     * @throws \Exception
     */
    private function updateProductWithLanguage(
        Shopware6Channel $channel,
        Language $language,
        AbstractProduct $product
    ): void {
        $shopwareLanguage = $this->languageRepository->load($channel->getId(), $language->getCode());
        Assert::notNull($shopwareLanguage);

        $shopwareProduct = $this->productClient->find($channel, $product, $shopwareLanguage);
        Assert::notNull($shopwareProduct);

        $this->updateProduct($channel, $shopwareProduct, $product, $language, $shopwareLanguage);
    }
}
