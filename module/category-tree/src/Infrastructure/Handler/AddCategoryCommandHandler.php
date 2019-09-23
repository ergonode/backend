<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Infrastructure\Handler;

use Ergonode\CategoryTree\Domain\Command\AddCategoryCommand;
use Ergonode\CategoryTree\Domain\Repository\TreeRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class AddCategoryCommandHandler
{
    /**
     * @var TreeRepositoryInterface
     */
    private $repository;

    /**
     * @param TreeRepositoryInterface $repository
     */
    public function __construct(TreeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param AddCategoryCommand $command
     */
    public function __invoke(AddCategoryCommand $command): void
    {
        $tree = $this->repository->load($command->getTreeId());

        Assert::notNull($tree);
        $tree->addCategory($command->getCategoryId(), $command->getParentId());

        $this->repository->save($tree);
    }
}
