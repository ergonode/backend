<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Infrastructure\Handler;

use Ergonode\Editor\Domain\Command\CreateProductDraftCommand;
use Ergonode\Editor\Domain\Entity\ProductDraft;
use Ergonode\Editor\Domain\Repository\ProductDraftRepositoryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;

/**
 * Class CreateProductCommandHandler
 */
class CreateProductDraftCommandHandler
{
    /**
     * @var ProductDraftRepositoryInterface
     */
    private ProductDraftRepositoryInterface $repository;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @param ProductDraftRepositoryInterface $repository
     * @param ProductRepositoryInterface      $productRepository
     */
    public function __construct(
        ProductDraftRepositoryInterface $repository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->repository = $repository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param CreateProductDraftCommand $command
     */
    public function __invoke(CreateProductDraftCommand $command)
    {
        $product = $this->productRepository->load($command->getProductId());

        $draft = new ProductDraft($command->getId(), $product);

        $this->repository->save($draft);
    }
}
