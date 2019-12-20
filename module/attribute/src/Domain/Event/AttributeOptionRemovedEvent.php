<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Event;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AttributeOptionRemovedEvent implements DomainEventInterface
{
    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\Attribute\Domain\Entity\AttributeId")
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\OptionKey")
     */
    private $key;

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
    public function getAggregateId(): AbstractId
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
