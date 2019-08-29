<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Infrastructure\Handler;

use Ergonode\CategoryTree\Domain\Command\UpdateTreeCommand;
use Ergonode\CategoryTree\Domain\Repository\TreeRepositoryInterface;
use Ergonode\CategoryTree\Domain\Updater\CategoryTreeUpdater;
use Webmozart\Assert\Assert;

/**
 */
class UpdateTreeCommandHandler
{
    /**
     * @var TreeRepositoryInterface
     */
    private $repository;
    /**
     * @var CategoryTreeUpdater
     */
    private $updater;

    /**
     * @param CategoryTreeUpdater     $updater
     * @param TreeRepositoryInterface $repository
     */
    public function __construct(CategoryTreeUpdater $updater, TreeRepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->updater = $updater;
    }

    /**
     * @param UpdateTreeCommand $command
     */
    public function __invoke(UpdateTreeCommand $command)
    {
        $categoryTree = $this->repository->load($command->getId());
        Assert::notNull($categoryTree);

        $categoryTree->updateCategories($command->getCategories());

        $categoryTree = $this->updater->update($categoryTree, $command->getName());
        $this->repository->save($categoryTree);
    }
}
