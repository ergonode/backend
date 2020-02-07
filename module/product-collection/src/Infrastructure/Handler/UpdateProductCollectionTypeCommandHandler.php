<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Infrastructure\Handler;

use Ergonode\ProductCollection\Domain\Command\UpdateProductCollectionTypeCommand;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionType;
use Ergonode\ProductCollection\Domain\Repository\ProductCollectionTypeRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class UpdateProductCollectionTypeCommandHandler
{
    /**
     * @var ProductCollectionTypeRepositoryInterface
     */
    private ProductCollectionTypeRepositoryInterface $repository;

    /**
     * @param ProductCollectionTypeRepositoryInterface $repository
     */
    public function __construct(ProductCollectionTypeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UpdateProductCollectionTypeCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UpdateProductCollectionTypeCommand $command)
    {
        /** @var ProductCollectionType $productCollectionType */
        $productCollectionType = $this->repository->load($command->getId());
        Assert::notNull($productCollectionType);

        $productCollectionType->changeName($command->getName());
        $this->repository->save($productCollectionType);
    }
}
