<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Infrastructure\Handler;

use Ergonode\ProductCollection\Domain\Command\AddProductCollectionElementCommand;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\ProductCollection\Domain\Repository\ProductCollectionRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class AddProductCollectionElementCommandHandler
{
    /**
     * @var ProductCollectionRepositoryInterface
     */
    private ProductCollectionRepositoryInterface $repository;

    /**
     * @param ProductCollectionRepositoryInterface $repository
     */
    public function __construct(ProductCollectionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param AddProductCollectionElementCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(AddProductCollectionElementCommand $command)
    {
        /** @var ProductCollection $productCollection */
        $productCollection = $this->repository->load($command->getProductCollectionId());

        Assert::notNull($productCollection);

        $productCollection->addElement($command->getProductId(), $command->isVisible());

        $this->repository->save($productCollection);
    }
}
