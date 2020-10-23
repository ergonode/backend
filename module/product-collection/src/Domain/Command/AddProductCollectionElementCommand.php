<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use JMS\Serializer\Annotation as JMS;

class AddProductCollectionElementCommand implements DomainCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId")
     */
    private ProductCollectionId $productCollectionId;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $productId;

    /**
     * @JMS\Type("bool")
     */
    private bool $visible;

    public function __construct(ProductCollectionId $productCollectionId, ProductId $productId, bool $visible = true)
    {
        $this->productCollectionId = $productCollectionId;
        $this->productId = $productId;
        $this->visible = $visible;
    }

    public function getProductCollectionId(): ProductCollectionId
    {
        return $this->productCollectionId;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }
}
