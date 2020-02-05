<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AddProductCollectionElementCommand implements DomainCommandInterface
{
    /**
     * @var ProductCollectionId
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionId")
     */
    private ProductCollectionId $productCollectionId;

    /**
     * @var ProductId
     *
     * @JMS\Type("Ergonode\Product\Domain\Entity\ProductId")
     */
    private ProductId $productId;

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     */
    private bool $visible;

    /**
     * AddProductCollectionElementCommand constructor.
     *
     * @param ProductCollectionId $productCollectionId
     * @param ProductId           $productId
     * @param bool                $visible
     */
    public function __construct(ProductCollectionId $productCollectionId, ProductId $productId, bool $visible)
    {
        $this->productCollectionId = $productCollectionId;
        $this->productId = $productId;
        $this->visible = $visible;
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

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }
}
