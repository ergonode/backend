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
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;

/**
 */
class CreateProductCollectionCommand implements DomainCommandInterface
{
    /**
     * @var ProductCollectionId
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionId")
     */
    private ProductCollectionId $id;

    /**
     * @var ProductCollectionCode
     *
     * @JMS/Type("Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode")
     *
     */
    private ProductCollectionCode $code;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    /**
     * @var ProductCollectionTypeId
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $typeId;

    /**
     * @param ProductCollectionCode   $code
     * @param TranslatableString      $name
     * @param ProductCollectionTypeId $typeId
     */
    public function __construct(ProductCollectionCode $code, TranslatableString $name, ProductCollectionTypeId $typeId)
    {
        $this->id = ProductCollectionId::fromCode($code);
        $this->code = $code;
        $this->name = $name;
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
     * @return ProductCollectionCode
     */
    public function getCode(): ProductCollectionCode
    {
        return $this->code;
    }

    /**
     * @return TranslatableString
     */
    public function getName(): TranslatableString
    {
        return $this->name;
    }

    /**
     * @return ProductCollectionTypeId
     */
    public function getTypeId(): ProductCollectionTypeId
    {
        return $this->typeId;
    }
}
