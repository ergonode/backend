<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Infrastructure\Handler\Tree;

use Ergonode\Category\Domain\Command\Tree\DeleteTreeCommand;
use Ergonode\Category\Domain\Entity\CategoryTree;
use Ergonode\Category\Domain\Repository\TreeRepositoryInterface;
use Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteTreeCommandHandler
{
    /**
     * @var TreeRepositoryInterface
     */
    private TreeRepositoryInterface $repository;

    /**
     * @var RelationshipsResolverInterface
     */
    private RelationshipsResolverInterface $relationshipsResolver;

    /**
     * @param TreeRepositoryInterface        $repository
     * @param RelationshipsResolverInterface $relationshipsResolver
     */
    public function __construct(
        TreeRepositoryInterface $repository,
        RelationshipsResolverInterface $relationshipsResolver
    ) {
        $this->repository = $repository;
        $this->relationshipsResolver = $relationshipsResolver;
    }

    /**
     * @param DeleteTreeCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(DeleteTreeCommand $command): void
    {
        $categoryTree = $this->repository->load($command->getId());
        Assert::isInstanceOf(
            $categoryTree,
            CategoryTree::class,
            sprintf(
                'Can\'t find category tree with ID "%s"',
                $command->getId()
            )
        );

        $relationships = $this->relationshipsResolver->resolve($command->getId());
        if (!$relationships->isEmpty()) {
            throw new ExistingRelationshipsException($command->getId());
        }

        $this->repository->delete($categoryTree);
    }
}
