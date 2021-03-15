<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Handler;

use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\ProductCollection\Domain\Repository\ProductCollectionRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\ProductCollection\Domain\Command\AddProductCollectionElementsCommand;

class AddProductCollectionElementsCommandHandler
{
    private ProductCollectionRepositoryInterface $repository;

    public function __construct(ProductCollectionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(AddProductCollectionElementsCommand $command): void
    {
        /** @var ProductCollection $productCollection */
        $productCollection = $this->repository->load($command->getProductCollectionId());

        Assert::notNull($productCollection);
        $productCollection->addElements($command->getProductIds());

        $this->repository->save($productCollection);
    }
}
