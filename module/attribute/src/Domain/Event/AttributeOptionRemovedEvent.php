<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AttributeOptionRemovedEvent implements DomainEventInterface
{
    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $id;

    /**
     * @var OptionKey
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\OptionKey")
     */
    private OptionKey $key;

    /**
     * @param AttributeId $id
     * @param OptionKey   $key
     */
    public function __construct(AttributeId $id, OptionKey $key)
    {
        $this->id = $id;
        $this->key = $key;
    }

    /**
     * @return AttributeId
     */
    public function getAggregateId(): AttributeId
    {
        return $this->id;
    }

    /**
     * @return OptionKey
     */
    public function getKey(): OptionKey
    {
        return $this->key;
    }
}
