<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TemplateSectionChangedEvent implements DomainEventInterface
{
    /**
     * @var int
     *
     * @JMS\Type("integer")
     */
    private $row;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $from;

    /**
     * @var $to
     *
     * @JMS\Type("string")
     */
    private $to;

    /**
     * @param int    $row
     * @param string $from
     * @param string $to
     */
    public function __construct(int $row, string $from, string $to)
    {
        $this->row = $row;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return int
     */
    public function getRow(): int
    {
        return $this->row;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }
}
