<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Infrastructure\Handler;

use Ergonode\ProductCollection\Domain\Command\DeleteProductCollectionTypeCommand;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionType;
use Ergonode\ProductCollection\Domain\Repository\ProductCollectionTypeRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteProductCollectionTypeCommandHandler
{
    /**
     * @var ProductCollectionTypeRepositoryInterface
     */
    private ProductCollectionTypeRepositoryInterface $repository;

    /**
     * @param ProductCollectionTypeRepositoryInterface $repository
     */
    public function __construct(
        ProductCollectionTypeRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }


    /**
     * @param DeleteProductCollectionTypeCommand $command
     *
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

        $this->repository->delete($productCollectionType);
    }
}
