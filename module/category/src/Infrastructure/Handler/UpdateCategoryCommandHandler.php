<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Infrastructure\Handler;

use Ergonode\Category\Domain\Command\UpdateCategoryCommand;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Category\Domain\Updater\CategoryUpdater;
use Webmozart\Assert\Assert;

/**
 */
class UpdateCategoryCommandHandler
{
    /**
     * @var CategoryUpdater
     */
    private $updater;
    /**
     * @var CategoryRepositoryInterface
     */
    private $repository;

    /**
     * @param CategoryUpdater             $updater
     * @param CategoryRepositoryInterface $repository
     */
    public function __construct(CategoryUpdater $updater, CategoryRepositoryInterface $repository)
    {
        $this->updater = $updater;
        $this->repository = $repository;
    }

    /**
     * @param UpdateCategoryCommand $command
     */
    public function __invoke(UpdateCategoryCommand $command)
    {
        $category = $this->repository->load($command->getId());
        Assert::notNull($category);

        $category = $this->updater->update($category, $command->getName());

        $this->repository->save($category);
    }
}
