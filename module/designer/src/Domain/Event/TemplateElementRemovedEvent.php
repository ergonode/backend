<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TemplateElementRemovedEvent implements DomainEventInterface
{
    /**
     * @var TemplateId
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateId")
     */
    private $id;

    /**
     * @var Position
     *
     * @JMS\Type("Ergonode\Designer\Domain\ValueObject\Position")
     */
    private $position;

    /**
     * @param TemplateId $id
     * @param Position   $position
     */
    public function __construct(TemplateId $id, Position $position)
    {
        $this->id = $id;
        $this->position = $position;
    }

    /**
     * @return AbstractId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return Position
     */
    public function getPosition(): Position
    {
        return $this->position;
    }
}
