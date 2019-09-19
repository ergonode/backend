<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Event\Attribute;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AttributeSystemChangedEvent implements DomainEventInterface
{
    /**
     * @var bool
     *
     * @JMS\Type("bool")
     */
    private $from;

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     */
    private $to;

    /**
     * @param bool $from
     * @param bool $to
     */
    public function __construct(bool $from, bool $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return bool
     */
    public function getFrom(): bool
    {
        return $this->from;
    }

    /**
     * @return bool
     */
    public function getTo(): bool
    {
        return $this->to;
    }
}
