<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Entity;

use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;
use Ergonode\Designer\Domain\ValueObject\TemplateElementPropertyInterface;
use JMS\Serializer\Annotation as JMS;

class TemplateElement
{
    /**
     * @JMS\Type("Ergonode\Designer\Domain\ValueObject\Position")
     */
    protected Position $position;

    /**
     * @JMS\Type("Ergonode\Designer\Domain\ValueObject\Size")
     */
    protected Size $size;

    /**
     * @JMS\Type("Ergonode\Designer\Domain\ValueObject\TemplateElementPropertyInterface")
     */
    protected TemplateElementPropertyInterface $properties;

    /**
     * @JMS\Type("string")
     */
    protected string $type;

    public function __construct(
        Position $position,
        Size $size,
        string $type,
        TemplateElementPropertyInterface $properties
    ) {
        $this->position = $position;
        $this->size = $size;
        $this->properties = $properties;
        $this->type = $type;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function getSize(): Size
    {
        return $this->size;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getProperties(): TemplateElementPropertyInterface
    {
        return $this->properties;
    }

    public function isEqual(TemplateElement $element): bool
    {
        return
            $element->getType() === $this->getType() &&
            $element->getProperties()->isEqual($this->getProperties()) &&
            $element->getSize()->isEqual($this->getSize()) &&
            $element->getPosition()->isEqual($this->getPosition());
    }
}
