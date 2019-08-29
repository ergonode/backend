<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Infrastructure\Handler;

use Ergonode\CategoryTree\Domain\Command\CreateTreeCommand;
use Ergonode\CategoryTree\Domain\Entity\CategoryTree;
use Ergonode\CategoryTree\Domain\Repository\TreeRepositoryInterface;

/**
 */
class CreateTreeCommandHandler
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
     * @param CreateTreeCommand $command
     */
    public function __invoke(CreateTreeCommand $command)
    {
        $tree = new CategoryTree($command->getId(), $command->getCode(), $command->getName());

        $this->repository->save($tree);
    }
}
