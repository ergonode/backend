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
class TemplateSectionAddedEvent implements DomainEventInterface
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
    private $section;

    /**
     * @param int    $row
     * @param string $section
     */
    public function __construct(int $row, string $section)
    {
        $this->row = $row;
        $this->section = $section;
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
    public function getSection(): string
    {
        return $this->section;
    }
}
