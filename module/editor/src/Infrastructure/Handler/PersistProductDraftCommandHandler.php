<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Editor\Infrastructure\Handler;

use Ergonode\Editor\Domain\Command\PersistProductDraftCommand;
use Ergonode\Editor\Domain\Entity\ProductDraft;
use Ergonode\Editor\Domain\Repository\ProductDraftRepositoryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;

class PersistProductDraftCommandHandler
{
    private ProductDraftRepositoryInterface $draftRepository;

    private ProductRepositoryInterface $productRepository;

    public function __construct(
        ProductDraftRepositoryInterface $draftRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->draftRepository = $draftRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(PersistProductDraftCommand $command): void
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
