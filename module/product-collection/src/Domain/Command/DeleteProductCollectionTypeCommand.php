<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionTypeId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class DeleteProductCollectionTypeCommand implements DomainCommandInterface
{
    /**
     * @var ProductCollectionTypeId
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $id;

    /**
     * @param ProductCollectionTypeId $id
     */
    public function __construct(ProductCollectionTypeId $id)
    {
        $this->id = $id;
    }

    /**
     * @return ProductCollectionTypeId
     */
    public function getId(): ProductCollectionTypeId
    {
        return $this->id;
    }
}
