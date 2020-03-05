<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Domain\Provider;

use Ergonode\Editor\Domain\Entity\ProductDraft;
use Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId;
use Ergonode\Editor\Domain\Query\DraftQueryInterface;
use Ergonode\Editor\Domain\Repository\ProductDraftRepositoryInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;

/**
 */
class DraftProvider
{
    /**
     * @var ProductDraftRepositoryInterface
     */
    private ProductDraftRepositoryInterface $repository;

    /**
     * @var DraftQueryInterface
     */
    private DraftQueryInterface $query;

    /**
     * @param ProductDraftRepositoryInterface $repository
     * @param DraftQueryInterface             $query
     */
    public function __construct(ProductDraftRepositoryInterface $repository, DraftQueryInterface $query)
    {
        $this->repository = $repository;
        $this->query = $query;
    }

    /**
     * @param AbstractProduct $product
     *
     * @return ProductDraft
     *
     * @throws \Exception
     */
    public function provide(AbstractProduct $product): ProductDraft
    {
        $draftId = $this->query->getActualDraftId($product->getId());

        if (null !== $draftId) {
            $draft = $this->repository->load($draftId);
        } else {
            $draftId = ProductDraftId::generate();
            $draft = new ProductDraft($draftId, $product);

            $this->repository->save($draft);
        }

        return $draft;
    }
}
