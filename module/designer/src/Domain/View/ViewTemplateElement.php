<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Designer\Domain\View;

use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;

class ViewTemplateElement
{
    /**
     * @var Position
     */
    private Position $position;

    /**
     * @var Size
     */
    private Size $size;

    /**
     * @var string
     */
    private string $label;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var array
     */
    private array $properties;

    /**
     * @param Position $position
     * @param Size     $size
     * @param string   $label
     * @param string   $type
     * @param array    $properties
     */
    public function __construct(Position $position, Size $size, string $label, string $type, array $properties = [])
    {
        $this->position = $position;
        $this->size = $size;
        $this->label = $label;
        $this->type = $type;
        $this->properties = $properties;
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
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }
}
