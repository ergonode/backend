<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Process;

use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Channel\Domain\ValueObject\ExportLineId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\LanguageRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Builder\CategoryBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6CategoryClient;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Category;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use GuzzleHttp\Exception\ClientException;
use Webmozart\Assert\Assert;

class CategoryShopware6ExportProcess
{
    private CategoryRepositoryInterface $shopware6CategoryRepository;

    private Shopware6CategoryClient $categoryClient;

    private CategoryBuilder $builder;

    private LanguageRepositoryInterface  $languageRepository;

    private ExportRepositoryInterface $exportRepository;

    public function __construct(
        CategoryRepositoryInterface $shopware6CategoryRepository,
        Shopware6CategoryClient $categoryClient,
        CategoryBuilder $builder,
        LanguageRepositoryInterface $languageRepository,
        ExportRepositoryInterface $exportRepository
    ) {
        $this->shopware6CategoryRepository = $shopware6CategoryRepository;
        $this->categoryClient = $categoryClient;
        $this->builder = $builder;
        $this->languageRepository = $languageRepository;
        $this->exportRepository = $exportRepository;
    }

    public function process(
        ExportLineId $lineId,
        Export $export,
        Shopware6Channel $channel,
        AbstractCategory $category,
        ?CategoryId $parentId = null
    ): void {
        $shopwareCategory = $this->loadCategory($channel, $category);
        if ($shopwareCategory) {
            $this->updateCategory($channel, $export, $shopwareCategory, $category, $parentId);
        } else {
            $shopwareCategory = new Shopware6Category();
            $this->builder->build($channel, $export, $shopwareCategory, $category, $parentId);
            $this->categoryClient->insert($channel, $shopwareCategory, $category);
        }

        foreach ($channel->getLanguages() as $language) {
            if ($this->languageRepository->exists($channel->getId(), $language->getCode())) {
                $this->updateCategoryWithLanguage($channel, $export, $language, $category, $parentId);
            }
        }

        $this->exportRepository->processLine($lineId);
    }

    private function updateCategory(
        Shopware6Channel $channel,
        Export $export,
        Shopware6Category $shopwareCategory,
        AbstractCategory $category,
        ?CategoryId $parentId = null,
        ?Language $language = null,
        ?Shopware6Language $shopwareLanguage = null
    ): void {
        $this->builder->build($channel, $export, $shopwareCategory, $category, $parentId, $language);
        if ($shopwareCategory->isModified()) {
            $this->categoryClient->update($channel, $shopwareCategory, $shopwareLanguage);
        }
    }

    private function updateCategoryWithLanguage(
        Shopware6Channel $channel,
        Export $export,
        Language $language,
        AbstractCategory $category,
        ?CategoryId $parentId = null
    ): void {
        $shopwareLanguage = $this->languageRepository->load($channel->getId(), $language->getCode());
        Assert::notNull($shopwareLanguage);

        $shopwareCategory = $this->loadCategory($channel, $category, $shopwareLanguage);
        Assert::notNull($shopwareCategory);

        $this->updateCategory($channel, $export, $shopwareCategory, $category, $parentId, $language, $shopwareLanguage);
    }

    private function loadCategory(
        Shopware6Channel $channel,
        AbstractCategory $category,
        ?Shopware6Language $shopware6Language = null
    ): ?Shopware6Category {
        $shopwareId = $this->shopware6CategoryRepository->load($channel->getId(), $category->getId());
        if ($shopwareId) {
            try {
                return $this->categoryClient->get($channel, $shopwareId, $shopware6Language);
            } catch (ClientException $exception) {
            }
        }

        return null;
    }
}
