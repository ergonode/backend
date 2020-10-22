<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Domain\Event;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

abstract class AbstractTranslatableStringBasedChangedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $from;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $to;

    public function __construct(TranslatableString $from, TranslatableString $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function getFrom(): TranslatableString
    {
        return $this->from;
    }

    public function getTo(): TranslatableString
    {
        return $this->to;
    }
}
