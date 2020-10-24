<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

abstract class AbstractStringBasedChangedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("string")
     */
    private ?string $from;

    /**
     * @JMS\Type("string")
     */
    private ?string $to;

    /**
     * @param ?string $from
     * @param ?string $to
     */
    public function __construct(?string $from, ?string $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function getFrom(): ?string
    {
        return $this->from;
    }

    public function getTo(): ?string
    {
        return $this->to;
    }
}
