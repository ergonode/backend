<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Process;

use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6CategoryRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6LanguageRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Builder\Shopware6CategoryBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6CategoryClient;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Category;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use GuzzleHttp\Exception\ClientException;
use Webmozart\Assert\Assert;

class CategoryShopware6ExportProcess
{
    /**
     * @var Shopware6CategoryRepositoryInterface
     */
    private Shopware6CategoryRepositoryInterface $shopware6CategoryRepository;

    /**
     * @var Shopware6CategoryClient
     */
    private Shopware6CategoryClient $categoryClient;

    /**
     * @var Shopware6CategoryBuilder
     */
    private Shopware6CategoryBuilder $builder;

    /**
     * @var Shopware6LanguageRepositoryInterface
     */
    private Shopware6LanguageRepositoryInterface  $languageRepository;

    /**
     * @param Shopware6CategoryRepositoryInterface $shopware6CategoryRepository
     * @param Shopware6CategoryClient              $categoryClient
     * @param Shopware6CategoryBuilder             $builder
     * @param Shopware6LanguageRepositoryInterface $languageRepository
     */
    public function __construct(
        Shopware6CategoryRepositoryInterface $shopware6CategoryRepository,
        Shopware6CategoryClient $categoryClient,
        Shopware6CategoryBuilder $builder,
        Shopware6LanguageRepositoryInterface $languageRepository
    ) {
        $this->shopware6CategoryRepository = $shopware6CategoryRepository;
        $this->categoryClient = $categoryClient;
        $this->builder = $builder;
        $this->languageRepository = $languageRepository;
    }

    /**
     * @param ExportId         $id
     * @param Shopware6Channel $channel
     * @param AbstractCategory $category
     * @param CategoryId|null  $parentId
     */
    public function process(
        ExportId $id,
        Shopware6Channel $channel,
        AbstractCategory $category,
        ?CategoryId $parentId = null
    ): void {
        $shopwareCategory = $this->loadCategory($channel, $category);
        if ($shopwareCategory) {
            $this->updateCategory($channel, $shopwareCategory, $category, $parentId);
        } else {
            $shopwareCategory = new Shopware6Category();
            $this->builder->build($channel, $shopwareCategory, $category, $parentId);
            $this->categoryClient->insert($channel, $shopwareCategory, $category);
        }

        foreach ($channel->getLanguages() as $language) {
            if ($this->languageRepository->exists($channel->getId(), $language->getCode())) {
                $this->updateCategoryWithLanguage($channel, $language, $category, $parentId);
            }
        }
    }

    /**
     * @param Shopware6Channel       $channel
     * @param Shopware6Category      $shopwareCategory
     * @param AbstractCategory       $category
     * @param CategoryId|null        $parentId
     * @param Language|null          $language
     * @param Shopware6Language|null $shopwareLanguage
     */
    private function updateCategory(
        Shopware6Channel $channel,
        Shopware6Category $shopwareCategory,
        AbstractCategory $category,
        ?CategoryId $parentId = null,
        ?Language $language = null,
        ?Shopware6Language $shopwareLanguage = null
    ): void {
        $this->builder->build($channel, $shopwareCategory, $category, $parentId, $language);
        if ($shopwareCategory->isModified()) {
            $this->categoryClient->update($channel, $shopwareCategory, $shopwareLanguage);
        }
    }

    /**
     * @param Shopware6Channel $channel
     * @param Language         $language
     * @param AbstractCategory $category
     * @param CategoryId|null  $parentId
     */
    private function updateCategoryWithLanguage(
        Shopware6Channel $channel,
        Language $language,
        AbstractCategory $category,
        ?CategoryId $parentId = null
    ): void {
        $shopwareLanguage = $this->languageRepository->load($channel->getId(), $language->getCode());
        Assert::notNull($shopwareLanguage);

        $shopwareCategory = $this->loadCategory($channel, $category, $shopwareLanguage);
        Assert::notNull($shopwareCategory);

        $this->updateCategory($channel, $shopwareCategory, $category, $parentId, $language, $shopwareLanguage);
    }

    /**
     * @param Shopware6Channel       $channel
     * @param AbstractCategory       $category
     * @param Shopware6Language|null $shopware6Language
     *
     * @return Shopware6Category|null
     */
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
