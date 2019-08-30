<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Infrastructure\Handler;

use Ergonode\CategoryTree\Domain\Command\UpdateTreeCommand;
use Ergonode\CategoryTree\Domain\Repository\TreeRepositoryInterface;
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
     * UpdateTreeCommandHandler constructor.
     *
     * @param TreeRepositoryInterface $repository
     */
    public function __construct(TreeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UpdateTreeCommand $command
     */
    public function __invoke(UpdateTreeCommand $command)
    {
        $categoryTree = $this->repository->load($command->getId());
        Assert::notNull($categoryTree);

        $categoryTree->updateCategories($command->getCategories());
        $categoryTree->changeName($command->getName());
        $this->repository->save($categoryTree);
    }
}
