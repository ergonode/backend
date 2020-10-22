<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Infrastructure\Handler;

use Ergonode\ProductCollection\Domain\Command\CreateProductCollectionCommand;
use Ergonode\ProductCollection\Domain\Factory\ProductCollectionFactory;
use Ergonode\ProductCollection\Domain\Repository\ProductCollectionRepositoryInterface;

class CreateProductCollectionCommandHandler
{
    /**
     * @var ProductCollectionRepositoryInterface
     */
    private ProductCollectionRepositoryInterface $repository;

    /**
     * @var ProductCollectionFactory
     */
    private ProductCollectionFactory $factory;

    /**
     * @param ProductCollectionRepositoryInterface $repository
     * @param ProductCollectionFactory             $factory
     */
    public function __construct(ProductCollectionRepositoryInterface $repository, ProductCollectionFactory $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @param CreateProductCollectionCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateProductCollectionCommand $command)
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
