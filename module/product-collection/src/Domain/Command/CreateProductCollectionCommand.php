<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Command;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CreateProductCollectionCommand implements DomainCommandInterface
{
    /**
     * @var ProductCollectionId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId")
     */
    private ProductCollectionId $id;

    /**
     * @var ProductCollectionCode
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode")
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
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $description;

    /**
     * @var ProductCollectionTypeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $typeId;

    /**
     * @param ProductCollectionCode   $code
     * @param TranslatableString      $name
     * @param TranslatableString      $description
     * @param ProductCollectionTypeId $typeId
     *
     * @throws \Exception
     */
    public function __construct(
        ProductCollectionCode $code,
        TranslatableString $name,
        TranslatableString $description,
        ProductCollectionTypeId $typeId
    ) {
        $this->id = ProductCollectionId::generate();
        $this->code = $code;
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

    /**
     * @return TranslatableString
     */
    public function getDescription(): TranslatableString
    {
        return $this->description;
    }
}
