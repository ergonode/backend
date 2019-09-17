<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Infrastructure\Handler;

use Ergonode\Category\Domain\Command\DeleteCategoryCommand;
use Ergonode\Category\Domain\Entity\Category;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteCategoryCommandHandler
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $repository;

    /**
     * @param CategoryRepositoryInterface $repository
     */
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param DeleteCategoryCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(DeleteCategoryCommand $command)
    {
        $category = $this->repository->load($command->getId());
        Assert::isInstanceOf($category, Category::class, sprintf('Can\'t find category with id "%s"', $command->getId()));

        $this->repository->delete($category);
    }
}
