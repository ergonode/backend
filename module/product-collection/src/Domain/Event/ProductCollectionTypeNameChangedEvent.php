<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use JMS\Serializer\Annotation as JMS;

class ProductCollectionTypeNameChangedEvent implements DomainEventInterface
{
    /**
     * @var ProductCollectionTypeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $id;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $from;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $to;

    /**
     * ProductCollectionTypeNameChangedEvent constructor.
     *
     * @param ProductCollectionTypeId $id
     * @param TranslatableString      $from
     * @param TranslatableString      $to
     */
    public function __construct(ProductCollectionTypeId $id, TranslatableString $from, TranslatableString $to)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return TranslatableString
     */
    public function getFrom(): TranslatableString
    {
        return $this->from;
    }

    /**
     * @return TranslatableString
     */
    public function getTo(): TranslatableString
    {
        return $this->to;
    }


    /**
     * @return ProductCollectionTypeId
     */
    public function getAggregateId(): ProductCollectionTypeId
    {
        return $this->id;
    }
}
