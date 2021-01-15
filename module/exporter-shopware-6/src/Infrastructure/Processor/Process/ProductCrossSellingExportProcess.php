<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Process;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\Channel\Domain\Entity\ExportLine;
use Ergonode\Channel\Domain\Repository\ExportLineRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\LanguageRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\ProductCrossSellingRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Builder\ProductCrossSellingBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Client\ProductCrossSellingClient;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Shopware6ExporterException;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractProductCrossSelling;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\ProductCrossSelling;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionElement;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use GuzzleHttp\Exception\ClientException;
use Webmozart\Assert\Assert;

class ProductCrossSellingExportProcess
{
    private ProductCrossSellingBuilder $builder;

    private ProductCrossSellingClient $productCrossSellingClient;

    private ProductCrossSellingRepositoryInterface $productCrossSellingRepository;

    private LanguageRepositoryInterface $languageRepository;

    private ExportLineRepositoryInterface $exportLineRepository;

    private ProductCrossSellingRemoveProductExportProcess $productCrossSellingRemoveExportProcess;

    public function __construct(
        ProductCrossSellingBuilder $builder,
        ProductCrossSellingClient $productCrossSellingClient,
        ProductCrossSellingRepositoryInterface $productCrossSellingRepository,
        LanguageRepositoryInterface $languageRepository,
        ExportLineRepositoryInterface $exportLineRepository,
        ProductCrossSellingRemoveProductExportProcess $productCrossSellingRemoveExportProcess
    ) {
        $this->builder = $builder;
        $this->productCrossSellingClient = $productCrossSellingClient;
        $this->productCrossSellingRepository = $productCrossSellingRepository;
        $this->languageRepository = $languageRepository;
        $this->exportLineRepository = $exportLineRepository;
        $this->productCrossSellingRemoveExportProcess = $productCrossSellingRemoveExportProcess;
    }

    public function process(Export $export, Shopware6Channel $channel, ProductCollection $productCollection): void
    {
        $this->productCrossSellingRemoveExportProcess->process($export, $channel, $productCollection);

        $exportLine = new ExportLine($export->getId(), $productCollection->getId());

        foreach ($productCollection->getElements() as $productCollectionElement) {
            if ($productCollectionElement->isVisible()) {
                $this->processElement($export, $channel, $productCollection, $productCollectionElement, $exportLine);
            }
        }

        $exportLine->process();
        $this->exportLineRepository->save($exportLine);
    }

    public function processElement(
        Export $export,
        Shopware6Channel $channel,
        ProductCollection $productCollection,
        ProductCollectionElement $collectionElement,
        ExportLine $exportLine
    ): void {
        $productCrossSelling = $this->loadProductCrossSelling(
            $channel,
            $productCollection->getId(),
            $collectionElement->getProductId()
        );
        try {
            if ($productCrossSelling) {
                $this->updateProductCrossSelling(
                    $channel,
                    $export,
                    $productCrossSelling,
                    $productCollection,
                    $collectionElement
                );
            } else {
                $productCrossSelling = new ProductCrossSelling();
                $productCrossSelling = $this->builder->build(
                    $channel,
                    $export,
                    $productCrossSelling,
                    $productCollection,
                    $collectionElement
                );
                $this->productCrossSellingClient->insert(
                    $channel,
                    $productCrossSelling,
                    $productCollection->getId(),
                    $collectionElement->getProductId()
                );
            }

            //update language
            foreach ($channel->getLanguages() as $language) {
                if ($this->languageRepository->exists($channel->getId(), $language->getCode())) {
                    $this->updateProductCrossSellingWithLanguage(
                        $channel,
                        $export,
                        $productCollection,
                        $collectionElement,
                        $language
                    );
                }
            }
        } catch (Shopware6ExporterException $exception) {
            $exportLine->process();
            $exportLine->addError($exception->getMessage(), $exception->getParameters());
            $this->exportLineRepository->save($exportLine);
        }
    }

    private function updateProductCrossSellingWithLanguage(
        Shopware6Channel $channel,
        Export $export,
        ProductCollection $productCollection,
        ProductCollectionElement $collectionElement,
        Language $language
    ): void {
        $shopwareLanguage = $this->languageRepository->load($channel->getId(), $language->getCode());
        Assert::notNull($shopwareLanguage);

        $productCrossSelling = $this->loadProductCrossSelling(
            $channel,
            $productCollection->getId(),
            $collectionElement->getProductId(),
            $shopwareLanguage
        );
        Assert::notNull($productCrossSelling);

        $this->updateProductCrossSelling(
            $channel,
            $export,
            $productCrossSelling,
            $productCollection,
            $collectionElement,
            $language,
            $shopwareLanguage
        );
    }

    private function updateProductCrossSelling(
        Shopware6Channel $channel,
        Export $export,
        AbstractProductCrossSelling $productCrossSelling,
        ProductCollection $productCollection,
        ProductCollectionElement $collectionElement,
        ?Language $language = null,
        ?Shopware6Language $shopwareLanguage = null
    ): void {
        $productCrossSelling = $this->builder->build(
            $channel,
            $export,
            $productCrossSelling,
            $productCollection,
            $collectionElement,
            $language
        );
        if ($productCrossSelling->isModified()) {
            $this->productCrossSellingClient->update(
                $channel,
                $productCrossSelling,
                $productCollection->getId(),
                $collectionElement->getProductId(),
                $shopwareLanguage
            );
        }
    }

    private function loadProductCrossSelling(
        Shopware6Channel $channel,
        ProductCollectionId $productCollectionId,
        ProductId $productId,
        ?Shopware6Language $shopware6Language = null
    ): ?AbstractProductCrossSelling {
        $shopwareId = $this->productCrossSellingRepository->load($channel->getId(), $productCollectionId, $productId);
        if ($shopwareId) {
            try {
                return $this->productCrossSellingClient->get($channel, $shopwareId, $shopware6Language);
            } catch (ClientException $exception) {
            }
        }

        return null;
    }
}
