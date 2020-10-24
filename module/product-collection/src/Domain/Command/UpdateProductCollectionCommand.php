<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Command;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use JMS\Serializer\Annotation as JMS;

class UpdateProductCollectionCommand implements DomainCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId")
     */
    private ProductCollectionId $id;

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

    public function getId(): ProductCollectionId
    {
        return $this->id;
    }

    public function getName(): TranslatableString
    {
        return $this->name;
    }

    public function getDescription(): TranslatableString
    {
        return $this->description;
    }

    public function getTypeId(): ProductCollectionTypeId
    {
        return $this->typeId;
    }
}
