<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Process;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\Exporter\Domain\Entity\ExportLine;
use Ergonode\Exporter\Domain\Repository\ExportLineRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6LanguageRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Builder\Shopware6ProductBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6ProductClient;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Shopware6ExporterException;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Webmozart\Assert\Assert;

class ProductShopware6ExportProcess
{
    private Shopware6ProductBuilder $builder;

    private Shopware6ProductClient $productClient;

    private Shopware6LanguageRepositoryInterface  $languageRepository;

    private ExportLineRepositoryInterface $exportLineRepository;

    public function __construct(
        Shopware6ProductBuilder $builder,
        Shopware6ProductClient $productClient,
        Shopware6LanguageRepositoryInterface $languageRepository,
        ExportLineRepositoryInterface $exportLineRepository
    ) {
        $this->builder = $builder;
        $this->productClient = $productClient;
        $this->languageRepository = $languageRepository;
        $this->exportLineRepository = $exportLineRepository;
    }

    /**
     * @throws \Exception
     */
    public function process(Export $export, Shopware6Channel $channel, AbstractProduct $product): void
    {
        $exportLine = new ExportLine($export->getId(), $product->getId());
        $shopwareProduct = $this->productClient->find($channel, $product);

        try {
            if ($shopwareProduct) {
                $this->updateProduct($channel, $export, $shopwareProduct, $product);
            } else {
                $shopwareProduct = new Shopware6Product();
                $this->builder->build($channel, $export, $shopwareProduct, $product);
                $this->productClient->insert($channel, $shopwareProduct, $product->getId());
            }

            foreach ($channel->getLanguages() as $language) {
                if ($this->languageRepository->exists($channel->getId(), $language->getCode())) {
                    $this->updateProductWithLanguage($channel, $export, $language, $product);
                }
            }
        } catch (Shopware6ExporterException $exception) {
            $exportLine->process();
            $exportLine->addError($exception->getMessage(), $exception->getParameters());
            $this->exportLineRepository->save($exportLine);
            throw $exception;
        }
        $exportLine->process();
        $this->exportLineRepository->save($exportLine);
    }

    private function updateProduct(
        Shopware6Channel $channel,
        Export $export,
        Shopware6Product $shopwareProduct,
        AbstractProduct $product,
        ?Language $language = null,
        ?Shopware6Language $shopwareLanguage = null
    ): void {
        $this->builder->build($channel, $export, $shopwareProduct, $product, $language);

        if ($shopwareProduct->isModified() || $shopwareProduct->hasItemToRemoved()) {
            $this->productClient->update($channel, $shopwareProduct, $shopwareLanguage);
        }
    }

    /**
     * @throws \Exception
     */
    private function updateProductWithLanguage(
        Shopware6Channel $channel,
        Export $export,
        Language $language,
        AbstractProduct $product
    ): void {
        $shopwareLanguage = $this->languageRepository->load($channel->getId(), $language->getCode());
        Assert::notNull($shopwareLanguage);

        $shopwareProduct = $this->productClient->find($channel, $product, $shopwareLanguage);
        Assert::notNull($shopwareProduct);

        $this->updateProduct($channel, $export, $shopwareProduct, $product, $language, $shopwareLanguage);
    }
}
