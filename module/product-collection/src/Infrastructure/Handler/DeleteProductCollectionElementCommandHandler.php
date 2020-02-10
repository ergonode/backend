<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Infrastructure\Handler;

use Ergonode\ProductCollection\Domain\Command\DeleteProductCollectionElementCommand;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\ProductCollection\Domain\Repository\ProductCollectionRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteProductCollectionElementCommandHandler
{
    /**
     * @var ProductCollectionRepositoryInterface
     */
    private ProductCollectionRepositoryInterface $repository;

    /**
     * DeleteProductCollectionElementCommandHandler constructor.
     *
     * @param ProductCollectionRepositoryInterface $repository
     */
    public function __construct(ProductCollectionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param DeleteProductCollectionElementCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(DeleteProductCollectionElementCommand $command)
    {
        /** @var ProductCollection $productCollection */
        $productCollection = $this->repository->load($command->getProductCollectionId());
        Assert::notNull($productCollection);

        $productCollection->removeElement($command->getProductId());

        $this->repository->save($productCollection);
    }
}
