<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Step;

use Ergonode\Category\Domain\Repository\TreeRepositoryInterface;
use Ergonode\Category\Domain\ValueObject\Node;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\ExporterShopware6\Domain\Command\Export\CategoryRemoveShopware6ExportCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Query\Shopware6CategoryQueryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Processor\Shopware6ExportStepProcessInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Webmozart\Assert\Assert;

/**
 */
class Shopware6CategoryRemoveStep implements Shopware6ExportStepProcessInterface
{
    /**
     * @var TreeRepositoryInterface
     */
    private TreeRepositoryInterface $treeRepository;

    /**
     * @var Shopware6CategoryQueryInterface
     */
    private Shopware6CategoryQueryInterface $shopwareCategoryQuery;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param TreeRepositoryInterface         $treeRepository
     * @param Shopware6CategoryQueryInterface $shopwareCategoryQuery
     * @param CommandBusInterface             $commandBus
     */
    public function __construct(
        TreeRepositoryInterface $treeRepository,
        Shopware6CategoryQueryInterface $shopwareCategoryQuery,
        CommandBusInterface $commandBus
    ) {
        $this->treeRepository = $treeRepository;
        $this->shopwareCategoryQuery = $shopwareCategoryQuery;
        $this->commandBus = $commandBus;
    }

    /**
     * @param ExportId         $exportId
     * @param Shopware6Channel $channel
     */
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
     * @param ExportId         $exportId
     * @param Shopware6Channel $channel
     * @param array            $categoryIds
     */
    private function categoryDelete(ExportId $exportId, Shopware6Channel $channel, array $categoryIds): void
    {
        $categoryList = $this->shopwareCategoryQuery->getCategoryToDelete($channel->getId(), $categoryIds);

        foreach ($categoryList as $category) {
            $categoryId = new CategoryId($category);
            $processCommand = new CategoryRemoveShopware6ExportCommand($exportId, $categoryId);
            $this->commandBus->dispatch($processCommand, true);
        }
    }

    /**
     * @param Node $node
     *
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
