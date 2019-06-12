<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Event;

use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AttributeOptionRemovedEvent implements DomainEventInterface
{
    /**
     * @var string
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\OptionKey")
     */
    private $key;

    /**
     * @param OptionKey $key
     */
    public function __construct(OptionKey $key)
    {
        $this->key = $key;
    }

    /**
     * @return OptionKey
     */
    public function getKey(): OptionKey
    {
        return $this->key;
    }
}
