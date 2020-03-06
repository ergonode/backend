<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CreateProductDraftCommand implements DomainCommandInterface
{
    /**
     * @var ProductDraftId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId")
     */
    private ProductDraftId $id;

    /**
     * @var ProductId|null
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ?ProductId $productId;

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
