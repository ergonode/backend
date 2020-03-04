<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Infrastructure\Handler;

use Ergonode\ProductCollection\Domain\Command\CreateProductCollectionTypeCommand;
use Ergonode\ProductCollection\Domain\Factory\ProductCollectionTypeFactory;
use Ergonode\ProductCollection\Domain\Repository\ProductCollectionTypeRepositoryInterface;

/**
 */
class CreateProductCollectionTypeCommandHandler
{
    /**
     * @var ProductCollectionTypeRepositoryInterface
     */
    private ProductCollectionTypeRepositoryInterface $repository;

    /**
     * @var ProductCollectionTypeFactory
     */
    private ProductCollectionTypeFactory $factory;

    /**
     * @param ProductCollectionTypeRepositoryInterface $repository
     * @param ProductCollectionTypeFactory             $factory
     */
    public function __construct(
        ProductCollectionTypeRepositoryInterface $repository,
        ProductCollectionTypeFactory $factory
    ) {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @param CreateProductCollectionTypeCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateProductCollectionTypeCommand $command)
    {
        $productCollectionType = $this->factory->create(
            $command->getId(),
            $command->getCode(),
            $command->getName(),
        );

        $this->repository->save($productCollectionType);
    }
}
