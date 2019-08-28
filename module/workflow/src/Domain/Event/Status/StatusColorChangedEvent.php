<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Status;

use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class StatusColorChangedEvent implements DomainEventInterface
{
    /**
     * @var Color
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Color")
     */
    private $from;

    /**
     * @var Color
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Color")
     */
    private $to;

    /**
     * @param Color $from
     * @param Color $to
     */
    public function __construct(Color $from, Color $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return Color
     */
    public function getFrom(): Color
    {
        return $this->from;
    }

    /**
     * @return Color
     */
    public function getTo(): Color
    {
        return $this->to;
    }
}
