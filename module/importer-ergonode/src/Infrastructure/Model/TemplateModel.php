<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Model;

/**
 */
final class TemplateModel
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var string
     */
    private string $x;

    /**
     * @var string
     */
    private string $y;

    /**
     * @var string
     */
    private string $width;

    /**
     * @var string
     */
    private string $height;

    /**
     * @param string $id
     * @param string $name
     * @param string $type
     * @param string $x
     * @param string $y
     * @param string $width
     * @param string $height
     */
    public function __construct(string $id, string $name, string $type, string $x, string $y, string $width, string $height)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->x = $x;
        $this->y = $y;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getX(): string
    {
        return $this->x;
    }

    /**
     * @return string
     */
    public function getY(): string
    {
        return $this->y;
    }

    /**
     * @return string
     */
    public function getWidth(): string
    {
        return $this->width;
    }

    /**
     * @return string
     */
    public function getHeight(): string
    {
        return $this->height;
    }
}