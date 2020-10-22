<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Command;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use JMS\Serializer\Annotation as JMS;

class CreateProductCollectionCommand implements DomainCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId")
     */
    private ProductCollectionId $id;

    /**
     * @JMS\Type("Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode")
     */
    private ProductCollectionCode $code;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $description;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $typeId;

    /**
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

    public function getId(): ProductCollectionId
    {
        return $this->id;
    }

    public function getCode(): ProductCollectionCode
    {
        return $this->code;
    }

    public function getName(): TranslatableString
    {
        return $this->name;
    }

    public function getTypeId(): ProductCollectionTypeId
    {
        return $this->typeId;
    }

    public function getDescription(): TranslatableString
    {
        return $this->description;
    }
}
