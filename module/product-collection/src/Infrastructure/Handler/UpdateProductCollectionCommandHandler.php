<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Handler;

use Ergonode\ProductCollection\Domain\Command\UpdateProductCollectionCommand;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\ProductCollection\Domain\Repository\ProductCollectionRepositoryInterface;
use Webmozart\Assert\Assert;

class UpdateProductCollectionCommandHandler
{
    private ProductCollectionRepositoryInterface $repository;

    public function __construct(ProductCollectionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateProductCollectionCommand $command): void
    {
        /** @var ProductCollection $productCollection */
        $productCollection = $this->repository->load($command->getId());
        Assert::notNull($productCollection);
        $productCollection->changeName($command->getName());
        $productCollection->changeDescription($command->getDescription());
        $productCollection->changeType($command->getTypeId());
        $this->repository->save($productCollection);
    }
}
