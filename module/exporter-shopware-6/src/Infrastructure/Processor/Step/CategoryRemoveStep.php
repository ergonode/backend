<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Step;

use Ergonode\Category\Domain\Repository\TreeRepositoryInterface;
use Ergonode\Category\Domain\ValueObject\Node;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\ExporterShopware6\Domain\Command\Export\CategoryRemoveExportCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Query\CategoryQueryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Processor\ExportStepProcessInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Webmozart\Assert\Assert;

class CategoryRemoveStep implements ExportStepProcessInterface
{
    private TreeRepositoryInterface $treeRepository;

    private CategoryQueryInterface $shopwareCategoryQuery;

    private CommandBusInterface $commandBus;

    public function __construct(
        TreeRepositoryInterface $treeRepository,
        CategoryQueryInterface $shopwareCategoryQuery,
        CommandBusInterface $commandBus
    ) {
        $this->treeRepository = $treeRepository;
        $this->shopwareCategoryQuery = $shopwareCategoryQuery;
        $this->commandBus = $commandBus;
    }

    public function export(ExportId $exportId, Shopware6Channel $channel): void
    {
        $categoryTreeId = $channel->getCategoryTree();
        if ($categoryTreeId) {
            $tree = $this->treeRepository->load($categoryTreeId);
            Assert::notNull($tree, sprintf('Tree %s not exists', $categoryTreeId));

            $categoryIds = [];
            foreach ($tree->getCategories() as $node) {
                $newCategoryIds = $this->buildStep($node);
                $categoryIds = array_unique(array_merge($categoryIds, $newCategoryIds));
            }

            $this->categoryDelete($exportId, $channel, $categoryIds);
        }
    }

    /**
     * @param array $categoryIds
     */
    private function categoryDelete(ExportId $exportId, Shopware6Channel $channel, array $categoryIds): void
    {
        $categoryList = $this->shopwareCategoryQuery->getCategoryToDelete($channel->getId(), $categoryIds);

        foreach ($categoryList as $category) {
            $categoryId = new CategoryId($category);
            $processCommand = new CategoryRemoveExportCommand($exportId, $categoryId);
            $this->commandBus->dispatch($processCommand, true);
        }
    }

    /**
     * @return array
     */
    private function buildStep(Node $node): array
    {
        $categoryIds[] = $node->getCategoryId()->getValue();
        foreach ($node->getChildren() as $child) {
            $newCategoryIds = $this->buildStep($child);
            $categoryIds = array_unique(array_merge($categoryIds, $newCategoryIds));
        }

        return $categoryIds;
    }
}
