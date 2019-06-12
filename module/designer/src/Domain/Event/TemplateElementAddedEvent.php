<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Designer\Domain\Entity\TemplateElementId;
use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TemplateElementAddedEvent implements DomainEventInterface
{
    /**
     * @var TemplateElementId
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateElementId")
     */
    private $elementId;

    /**
     * @var Position
     *
     * @JMS\Type("Ergonode\Designer\Domain\ValueObject\Position")
     */
    private $position;

    /**
     * @var Size
     *
     * @JMS\Type("Ergonode\Designer\Domain\ValueObject\Size")
     */
    private $size;

    /**
     * @var bool
     *
     * @JMS\Type("boolean")
     */
    private $required;

    /**
     * @param TemplateElementId $elementId
     * @param Position          $position
     * @param Size              $size
     * @param bool              $required
     */
    public function __construct(TemplateElementId $elementId, Position $position, Size $size, bool $required = false)
    {
        $this->elementId = $elementId;
        $this->position = $position;
        $this->size = $size;
        $this->required = $required;
    }

    /**
     * @return TemplateElementId
     */
    public function getElementId(): TemplateElementId
    {
        return $this->elementId;
    }

    /**
     * @return Position
     */
    public function getPosition(): Position
    {
        return $this->position;
    }

    /**
     * @return Size
     */
    public function getSize(): Size
    {
        return $this->size;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }
}
