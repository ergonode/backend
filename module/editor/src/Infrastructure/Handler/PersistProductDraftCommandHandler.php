<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Infrastructure\Handler;

use Ergonode\Editor\Domain\Command\PersistProductDraftCommand;
use Ergonode\Editor\Domain\Entity\ProductDraft;
use Ergonode\Editor\Domain\Repository\ProductDraftRepositoryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;

/**
 * Class PersistProductDraftCommandHandler
 */
class PersistProductDraftCommandHandler
{
    /**
     * @var ProductDraftRepositoryInterface
     */
    private $draftRepository;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param ProductDraftRepositoryInterface $draftRepository
     * @param ProductRepositoryInterface      $productRepository
     */
    public function __construct(ProductDraftRepositoryInterface $draftRepository, ProductRepositoryInterface $productRepository)
    {
        $this->draftRepository = $draftRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param PersistProductDraftCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(PersistProductDraftCommand $command)
    {
        /** @var ProductDraft $draft */
        $draft = $this->draftRepository->load($command->getId());

        $product = $this->productRepository->load($draft->getProductId());

        if ($product) {
            $product->applyDraft($draft);
            $draft->applied();

            $this->draftRepository->save($draft);
            $this->productRepository->save($product);
        }
    }
}
