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
class TemplateSectionRemovedEvent implements DomainEventInterface
{
    /**
     * @var int
     *
     * @JMS\Type("integer")
     */
    private $row;

    /**
     * @param int $row
     */
    public function __construct(int $row)
    {
        $this->row = $row;
    }

    /**
     * @return int
     */
    public function getRow(): int
    {
        return $this->row;
    }
}
