<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Infrastructure\Handler\Tree;

use Ergonode\Category\Domain\Command\Tree\CreateTreeCommand;
use Ergonode\Category\Domain\Entity\CategoryTree;
use Ergonode\Category\Domain\Repository\TreeRepositoryInterface;

/**
 */
class CreateTreeCommandHandler
{
    /**
     * @var TreeRepositoryInterface
     */
    private TreeRepositoryInterface $repository;

    /**
     * @param TreeRepositoryInterface $repository
     */
    public function __construct(TreeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CreateTreeCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateTreeCommand $command): void
    {
        $tree = new CategoryTree($command->getId(), $command->getCode(), $command->getName());

        $this->repository->save($tree);
    }
}
