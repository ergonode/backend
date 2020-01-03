<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Command;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Product\Domain\Entity\ProductId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

/**
 */
class UpdateProductCommand implements DomainCommandInterface
{
    /**
     * @var ProductId
     *
     * @JMS\Type("Ergonode\Product\Domain\Entity\ProductId")
     */
    private $id;

    /**
     * @var CategoryId[]
     *
     * @JMS\Type("array<string, Ergonode\Category\Domain\Entity\CategoryId>")
     */
    private $categories;

    /**
     * @param ProductId $productId
     * @param array     $categories
     */
    public function __construct(ProductId $productId, array $categories = [])
    {
        $this->id = $productId;
        $this->categories = $categories;
    }

    /**
     * @return ProductId
     */
    public function getId(): ProductId
    {
        return $this->id;
    }

    /**
     * @return CategoryId[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }
}
