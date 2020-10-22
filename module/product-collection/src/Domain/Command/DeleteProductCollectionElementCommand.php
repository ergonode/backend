<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use JMS\Serializer\Annotation as JMS;

class DeleteProductCollectionElementCommand implements DomainCommandInterface
{
    /**
     * @var ProductCollectionId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId")
     */
    private ProductCollectionId $productCollectionId;

    /**
     * @var ProductId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $productId;

    /**
     * DeleteProductCollectionElementCommand constructor.
     *
     * @param ProductCollectionId $productCollectionId
     * @param ProductId           $productId
     */
    public function __construct(ProductCollectionId $productCollectionId, ProductId $productId)
    {
        $this->productCollectionId = $productCollectionId;
        $this->productId = $productId;
    }

    /**
     * @return ProductCollectionId
     */
    public function getProductCollectionId(): ProductCollectionId
    {
        return $this->productCollectionId;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }
}
