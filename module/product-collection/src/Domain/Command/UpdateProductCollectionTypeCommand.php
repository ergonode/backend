<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Command;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use JMS\Serializer\Annotation as JMS;

class UpdateProductCollectionTypeCommand implements DomainCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $id;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    public function __construct(
        ProductCollectionTypeId $id,
        TranslatableString $name
    ) {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): ProductCollectionTypeId
    {
        return $this->id;
    }

    public function getName(): TranslatableString
    {
        return $this->name;
    }
}
