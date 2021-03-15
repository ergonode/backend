<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler;

use Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Product\Domain\Command\DeleteProductCommand;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Webmozart\Assert\Assert;

class DeleteProductCommandHandler
{
    private ProductRepositoryInterface $repository;

    private RelationshipsResolverInterface $relationshipsResolver;

    public function __construct(
        ProductRepositoryInterface $repository,
        RelationshipsResolverInterface $relationshipsResolver
    ) {
        $this->repository = $repository;
        $this->relationshipsResolver = $relationshipsResolver;
    }

    /**
     * @throws ExistingRelationshipsException
     */
    public function __invoke(DeleteProductCommand $command): void
    {
        $product = $this->repository->load($command->getId());
        Assert::isInstanceOf(
            $product,
            AbstractProduct::class,
            sprintf('Can\'t find product with id "%s"', $command->getId())
        );

        $relationships = $this->relationshipsResolver->resolve($command->getId());
        if (null !== $relationships) {
            throw new ExistingRelationshipsException($command->getId());
        }

        $this->repository->delete($product);
    }
}
