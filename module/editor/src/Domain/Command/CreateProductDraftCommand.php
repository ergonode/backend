<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Domain\Command;

use Ergonode\Editor\Domain\Entity\ProductDraftId;
use Ergonode\Product\Domain\Entity\ProductId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

/**
 */
class CreateProductDraftCommand implements DomainCommandInterface
{
    /**
     * @var ProductDraftId
     *
     * @JMS\Type("Ergonode\Editor\Domain\Entity\ProductDraftId")
     */
    private $id;

    /**
     * @var ProductId|null
     *
     * @JMS\Type("Ergonode\Product\Domain\Entity\ProductId")
     */
    private $productId;

    /**
     * @param ProductId|null $productId
     *
     * @throws \Exception
     */
    public function __construct(ProductId $productId = null)
    {
        $this->id = ProductDraftId::generate();
        $this->productId = $productId;
    }

    /**
     * @return ProductDraftId
     */
    public function getId(): ProductDraftId
    {
        return $this->id;
    }

    /**
     * @return ProductId|null
     */
    public function getProductId(): ?ProductId
    {
        return $this->productId;
    }
}
