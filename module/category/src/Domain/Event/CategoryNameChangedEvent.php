<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Domain\Event;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CategoryNameChangedEvent implements DomainEventInterface
{
    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $from;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $to;

    /**
     * @param TranslatableString $from
     * @param TranslatableString $to
     */
    public function __construct(TranslatableString $from, TranslatableString $to)
    {
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
}
