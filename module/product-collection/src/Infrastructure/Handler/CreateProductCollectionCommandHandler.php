<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Handler;

use Ergonode\ProductCollection\Domain\Command\CreateProductCollectionCommand;
use Ergonode\ProductCollection\Domain\Factory\ProductCollectionFactory;
use Ergonode\ProductCollection\Domain\Repository\ProductCollectionRepositoryInterface;

class CreateProductCollectionCommandHandler
{
    private ProductCollectionRepositoryInterface $repository;

    private ProductCollectionFactory $factory;

    public function __construct(ProductCollectionRepositoryInterface $repository, ProductCollectionFactory $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(CreateProductCollectionCommand $command): void
    {
        $productCollection = $this->factory->create(
            $command->getId(),
            $command->getCode(),
            $command->getName(),
            $command->getDescription(),
            $command->getTypeId()
        );
        $this->repository->save($productCollection);
    }
}
