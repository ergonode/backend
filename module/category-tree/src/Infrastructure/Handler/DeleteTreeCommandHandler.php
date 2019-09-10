<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Infrastructure\Handler;

use Ergonode\CategoryTree\Domain\Command\DeleteTreeCommand;
use Ergonode\CategoryTree\Domain\Entity\CategoryTree;
use Ergonode\CategoryTree\Domain\Repository\TreeRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteTreeCommandHandler
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
     * @param DeleteTreeCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(DeleteTreeCommand $command)
    {
        $categoryTree = $this->repository->load($command->getId());
        Assert::isInstanceOf($categoryTree, CategoryTree::class, sprintf('Can\'t find category tree with id "%s"', $command->getId()));

        $this->repository->delete($categoryTree);
    }
}
