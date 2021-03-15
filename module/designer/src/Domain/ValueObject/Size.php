<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\ValueObject;

use Webmozart\Assert\Assert;

class Size
{
    private int $width;

    private int $height;

    public function __construct(int $width, int $height)
    {
        Assert::greaterThanEq($width, 0);
        Assert::greaterThanEq($height, 0);

        $this->width = $width;
        $this->height = $height;
    }

    public function isEqual(Size $object): bool
    {
        return $object->getWidth() === $this->getWidth() && $object->getHeight() === $this->getHeight();
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }
}
