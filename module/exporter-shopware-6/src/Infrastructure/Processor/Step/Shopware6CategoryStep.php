<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Step;

use Ergonode\Category\Domain\Entity\CategoryTree;
use Ergonode\Category\Domain\Repository\TreeRepositoryInterface;
use Ergonode\Category\Domain\ValueObject\Node;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\ExporterShopware6\Domain\Command\Export\CategoryExportCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Processor\Shopware6ExportStepProcessInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Webmozart\Assert\Assert;

class Shopware6CategoryStep implements Shopware6ExportStepProcessInterface
{
    private TreeRepositoryInterface $treeRepository;

    private CommandBusInterface $commandBus;

    public function __construct(TreeRepositoryInterface $treeRepository, CommandBusInterface $commandBus)
    {
        $this->treeRepository = $treeRepository;
        $this->commandBus = $commandBus;
    }

    public function export(ExportId $exportId, Shopware6Channel $channel): void
    {
        $categoryTreeId = $channel->getCategoryTree();
        if ($categoryTreeId) {
            /** @var CategoryTree $tree */
            $tree = $this->treeRepository->load($categoryTreeId);
            Assert::notNull($tree, sprintf('Tree %s not exists', $categoryTreeId));
            foreach ($tree->getCategories() as $node) {
                $this->buildStep($exportId, $node);
            }
        }
    }

    private function buildStep(ExportId $exportId, Node $node, CategoryId $parentId = null): void
    {
        $processCommand = new CategoryExportCommand($exportId, $node->getCategoryId(), $parentId);
        $this->commandBus->dispatch($processCommand, true);

        $newParent = $node->getCategoryId();
        foreach ($node->getChildren() as $child) {
            $this->buildStep($exportId, $child, $newParent);
        }
    }
}
