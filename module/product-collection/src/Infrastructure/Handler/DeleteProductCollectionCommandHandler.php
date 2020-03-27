<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Infrastructure\Handler;

use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\ProductCollection\Domain\Command\DeleteProductCollectionCommand;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\ProductCollection\Domain\Repository\ProductCollectionRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteProductCollectionCommandHandler
{
    /**
     * @var ProductCollectionRepositoryInterface
     */
    private ProductCollectionRepositoryInterface $repository;

    /**
     * @var RelationshipsResolverInterface
     */
    private RelationshipsResolverInterface $relationshipsResolver;

    /**
     * DeleteProductCollectionCommandHandler constructor.
     *
     * @param ProductCollectionRepositoryInterface $repository
     * @param RelationshipsResolverInterface       $relationshipsResolver
     */
    public function __construct(
        ProductCollectionRepositoryInterface $repository,
        RelationshipsResolverInterface $relationshipsResolver
    ) {
        $this->repository = $repository;
        $this->relationshipsResolver = $relationshipsResolver;
    }


    /**
     * @param DeleteProductCollectionCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(DeleteProductCollectionCommand $command): void
    {
        /** @var ProductCollection $productCollection */
        $productCollection = $this->repository->load($command->getId());
        Assert::isInstanceOf(
            $productCollection,
            ProductCollection::class,
            sprintf('Can\'t find product collection with id "%s"', $command->getId())
        );

        $productCollectionElements = $productCollection->getElements();
        foreach ($productCollectionElements as $element) {
            $productCollection->removeElement($element->getProductId());
        }

        $this->repository->delete($productCollection);
    }
}
