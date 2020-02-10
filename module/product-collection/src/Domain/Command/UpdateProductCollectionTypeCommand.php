<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Command;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionTypeId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class UpdateProductCollectionTypeCommand implements DomainCommandInterface
{
    /**
     * @var ProductCollectionTypeId
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $id;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    /**
     * @param ProductCollectionTypeId $id
     * @param TranslatableString      $name
     */
    public function __construct(
        ProductCollectionTypeId $id,
        TranslatableString $name
    ) {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return ProductCollectionTypeId
     */
    public function getId(): ProductCollectionTypeId
    {
        return $this->id;
    }

    /**
     * @return TranslatableString
     */
    public function getName(): TranslatableString
    {
        return $this->name;
    }
}
