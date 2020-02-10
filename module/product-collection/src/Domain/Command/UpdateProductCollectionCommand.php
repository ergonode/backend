<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Command;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionId;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionTypeId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class UpdateProductCollectionCommand implements DomainCommandInterface
{
    /**
     * @var ProductCollectionId
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionId")
     */
    private ProductCollectionId $id;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $description;

    /**
     * @var ProductCollectionTypeId
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $typeId;

    /**
     * @param ProductCollectionId     $id
     * @param TranslatableString      $name
     * @param TranslatableString      $description
     * @param ProductCollectionTypeId $typeId
     */
    public function __construct(
        ProductCollectionId $id,
        TranslatableString $name,
        TranslatableString $description,
        ProductCollectionTypeId $typeId
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->typeId = $typeId;
    }

    /**
     * @return ProductCollectionId
     */
    public function getId(): ProductCollectionId
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

    /**
     * @return TranslatableString
     */
    public function getDescription(): TranslatableString
    {
        return $this->description;
    }

    /**
     * @return ProductCollectionTypeId
     */
    public function getTypeId(): ProductCollectionTypeId
    {
        return $this->typeId;
    }
}
