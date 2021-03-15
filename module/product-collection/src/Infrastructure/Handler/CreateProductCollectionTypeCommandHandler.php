<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Handler;

use Ergonode\ProductCollection\Domain\Command\CreateProductCollectionTypeCommand;
use Ergonode\ProductCollection\Domain\Factory\ProductCollectionTypeFactory;
use Ergonode\ProductCollection\Domain\Repository\ProductCollectionTypeRepositoryInterface;

class CreateProductCollectionTypeCommandHandler
{
    private ProductCollectionTypeRepositoryInterface $repository;

    private ProductCollectionTypeFactory $factory;

    public function __construct(
        ProductCollectionTypeRepositoryInterface $repository,
        ProductCollectionTypeFactory $factory
    ) {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(CreateProductCollectionTypeCommand $command): void
    {
        $productCollectionType = $this->factory->create(
            $command->getId(),
            $command->getCode(),
            $command->getName(),
        );

        $this->repository->save($productCollectionType);
    }
}
