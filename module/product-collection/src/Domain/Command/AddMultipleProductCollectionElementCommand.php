<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AddMultipleProductCollectionElementCommand implements DomainCommandInterface
{
    /**
     * @var ProductCollectionId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId")
     */
    private ProductCollectionId $productCollectionId;

    /**
     * @var ProductId[]
     *
     * @JMS\Type("array")
     */
    private array $productIds;

    /**
     * AddMultipleProductCollectionElementCommand constructor.
     *
     * @param ProductCollectionId $productCollectionId
     * @param array|ProductId[]   $productIds
     */
    public function __construct(ProductCollectionId $productCollectionId, $productIds)
    {
        $this->productCollectionId = $productCollectionId;
        $this->productIds = $productIds;
    }

    /**
     * @return ProductCollectionId
     */
    public function getProductCollectionId(): ProductCollectionId
    {
        return $this->productCollectionId;
    }

    /**
     * @return ProductId[]
     */
    public function getProductIds(): array
    {
        return $this->productIds;
    }
}
