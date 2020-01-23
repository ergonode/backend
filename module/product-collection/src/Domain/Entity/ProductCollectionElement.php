<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Entity;

use Ergonode\EventSourcing\Domain\AbstractEntity;
use Ergonode\Product\Domain\Entity\ProductId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductCollectionElement extends AbstractEntity
{
    /**
     * @var ProductCollectionElementId
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionElementId")
     */
    private ProductCollectionElementId $id;

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
     * ProductCollectionElement constructor.
     *
     * @param ProductCollectionElementId $id
     * @param ProductId                  $productId
     * @param bool                       $visible
     */
    public function __construct(ProductCollectionElementId $id, ProductId $productId, bool $visible)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->visible = $visible;
    }

    /**
     * @return ProductCollectionElementId
     */
    public function getId(): ProductCollectionElementId
    {
        return $this->id;
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
