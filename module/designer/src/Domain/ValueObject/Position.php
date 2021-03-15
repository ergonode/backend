<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\ValueObject;

use Webmozart\Assert\Assert;

class Position
{
    private int $x;

    private int $y;

    public function __construct(int $x, int $y)
    {
        Assert::greaterThanEq($x, 0);
        Assert::greaterThanEq($y, 0);

        $this->x = $x;
        $this->y = $y;
    }

    public function isEqual(Position $object): bool
    {
        return $object->getY() === $this->getY() && $object->getX() === $this->getX();
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function __toString(): string
    {
        return sprintf('%sx%s', $this->x, $this->y);
    }
}
