<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Entity;

use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TemplateElement
{
    /**
     * @var TemplateElementId
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateElementId")
     */
    private $elementId;

    /**
     * @JMS\Type("Ergonode\Designer\Domain\ValueObject\Position")
     */
    private $position;

    /**
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

    /**
     * @param Position $position
     */
    public function setPosition(Position $position): void
    {
        $this->position = $position;
    }

    /**
     * @param Size $size
     */
    public function setSize(Size $size): void
    {
        $this->size = $size;
    }

    /**
     * @param bool $required
     */
    public function setRequired(bool $required): void
    {
        $this->required = $required;
    }
}
