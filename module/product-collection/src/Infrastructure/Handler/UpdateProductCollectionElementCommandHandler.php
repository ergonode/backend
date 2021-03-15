<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Handler;

use Ergonode\ProductCollection\Domain\Command\UpdateProductCollectionElementCommand;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\ProductCollection\Domain\Repository\ProductCollectionRepositoryInterface;
use Webmozart\Assert\Assert;

class UpdateProductCollectionElementCommandHandler
{
    private ProductCollectionRepositoryInterface $repository;

    public function __construct(ProductCollectionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateProductCollectionElementCommand $command): void
    {
        /** @var ProductCollection $productCollection */
        $productCollection = $this->repository->load($command->getProductCollectionId());
        Assert::notNull($productCollection);

        if ($productCollection->hasElement($command->getProductId())) {
            $productCollection->getElement($command->getProductId())->changeVisible($command->isVisible());
        }

        $this->repository->save($productCollection);
    }
}
