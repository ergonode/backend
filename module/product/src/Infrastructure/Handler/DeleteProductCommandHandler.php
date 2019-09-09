<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Handler;

use Ergonode\Product\Domain\Command\DeleteProductCommand;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteProductCommandHandler
{
    /**
     * @var ProductRepositoryInterface
     */
    private $repository;

    /**
     * @param ProductRepositoryInterface $repository
     */
    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param DeleteProductCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(DeleteProductCommand $command)
    {
        $role = $this->repository->load($command->getId());
        Assert::isInstanceOf($role, AbstractProduct::class, sprintf('Can\'t find product with id "%s"', $command->getId()));

        $this->repository->delete($role);
    }
}
