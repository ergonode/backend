<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionId;

/**
 */
class DeleteProductCollectionCommand implements DomainCommandInterface
{
    /**
     * @var ProductCollectionId
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionId")
     */
    private ProductCollectionId $id;

    /**
     * DeleteProductCollectionCommand constructor.
     *
     * @param ProductCollectionId $id
     */
    public function __construct(ProductCollectionId $id)
    {
        $this->id = $id;
    }

    /**
     * @return ProductCollectionId
     */
    public function getId(): ProductCollectionId
    {
        return $this->id;
    }
}
