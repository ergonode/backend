<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Handler;

use Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\ProductCollection\Domain\Command\DeleteProductCollectionTypeCommand;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionType;
use Ergonode\ProductCollection\Domain\Repository\ProductCollectionTypeRepositoryInterface;
use Webmozart\Assert\Assert;

class DeleteProductCollectionTypeCommandHandler
{
    private ProductCollectionTypeRepositoryInterface $repository;

    private RelationshipsResolverInterface $relationshipsResolver;

    public function __construct(
        ProductCollectionTypeRepositoryInterface $repository,
        RelationshipsResolverInterface $relationshipsResolver
    ) {
        $this->repository = $repository;
        $this->relationshipsResolver = $relationshipsResolver;
    }


    /**
     * @throws \Exception
     */
    public function __invoke(DeleteProductCollectionTypeCommand $command): void
    {
        $productCollectionType = $this->repository->load($command->getId());
        Assert::isInstanceOf(
            $productCollectionType,
            ProductCollectionType::class,
            sprintf('Can\'t find product collection type with id "%s"', $command->getId())
        );

        $relationships = $this->relationshipsResolver->resolve($command->getId());
        if (null !== $relationships) {
            throw new ExistingRelationshipsException($command->getId());
        }

        $this->repository->delete($productCollectionType);
    }
}
