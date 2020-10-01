<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Step;

use Ergonode\Category\Domain\ValueObject\Node;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Exporter\Domain\Repository\TreeRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Command\Export\CategoryShopware6ExportCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Processor\Shopware6ExportStepProcessInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 */
class Shopware6CategoryStep implements Shopware6ExportStepProcessInterface
{
    /**
     * @var TreeRepositoryInterface
     */
    private TreeRepositoryInterface $treeRepository;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param TreeRepositoryInterface $treeRepository
     * @param CommandBusInterface     $commandBus
     */
    public function __construct(TreeRepositoryInterface $treeRepository, CommandBusInterface $commandBus)
    {
        $this->treeRepository = $treeRepository;
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
            $tree = $this->treeRepository->load(Uuid::fromString($categoryTreeId->getValue()));
            Assert::notNull($tree, sprintf('Tree %s not exists', $categoryTreeId));

            foreach ($tree->getCategories() as $node) {
                $this->buildStep($exportId, $node);
            }
        }
    }

    /**
     * @param ExportId        $exportId
     * @param Node            $node
     * @param CategoryId|null $parentId
     */
    private function buildStep(ExportId $exportId, Node $node, CategoryId $parentId = null): void
    {
        $processCommand = new CategoryShopware6ExportCommand($exportId, $node->getCategoryId(), $parentId);
        $this->commandBus->dispatch($processCommand, true);

        $newParent = $node->getCategoryId();
        foreach ($node->getChildren() as $child) {
            $this->buildStep($exportId, $child, $newParent);
        }
    }
}
