<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Command;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CreateProductCollectionTypeCommand implements DomainCommandInterface
{
    /**
     * @var ProductCollectionTypeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $id;

    /**
     * @var ProductCollectionTypeCode
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode")
     *
     */
    private ProductCollectionTypeCode $code;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    /**
     * @param ProductCollectionTypeCode $code
     * @param TranslatableString        $name
     *
     * @throws \Exception
     */
    public function __construct(
        ProductCollectionTypeCode $code,
        TranslatableString $name
    ) {
        $this->id = ProductCollectionTypeId::generate();
        $this->code = $code;
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
     * @return ProductCollectionTypeCode
     */
    public function getCode(): ProductCollectionTypeCode
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
}
