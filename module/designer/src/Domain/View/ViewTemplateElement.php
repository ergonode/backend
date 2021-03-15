<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\View;

use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;

class ViewTemplateElement
{
    private Position $position;

    private Size $size;

    private string $label;

    private string $type;

    /**
     * @var array
     */
    private array $properties;

    /**
     * @param array $properties
     */
    public function __construct(Position $position, Size $size, string $label, string $type, array $properties = [])
    {
        $this->position = $position;
        $this->size = $size;
        $this->label = $label;
        $this->type = $type;
        $this->properties = $properties;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function getSize(): Size
    {
        return $this->size;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

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
