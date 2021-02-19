<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Model;

class TemplateElementModel
{
    private string $name;
    private string $type;
    private int $x;
    private int $y;
    private int $width;
    private int $height;
    private array $properties;

    public function __construct(
        string $name,
        string $type,
        int $x,
        int $y,
        int $width,
        int $height,
        array $properties
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->x = $x;
        $this->y = $y;
        $this->width = $width;
        $this->height = $height;
        $this->properties = $properties;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'x' => $this->x,
            'y' => $this->y,
            'width' => $this->width,
            'height' => $this->height,
            'properties' => $this->properties,
        ];
    }
}
