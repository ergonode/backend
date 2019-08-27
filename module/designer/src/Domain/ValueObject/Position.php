<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\ValueObject;

use Webmozart\Assert\Assert;

/**
 */
class Position
{
    /**
     * @var int
     */
    private $x;

    /**
     * @var int
     */
    private $y;

    /**
     * @param int $x
     * @param int $y
     */
    public function __construct(int $x, int $y)
    {
        Assert::greaterThanEq($x, 0);
        Assert::greaterThanEq($y, 0);

        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @param Position $object
     *
     * @return bool
     */
    public function isEqual(Position $object): bool
    {
        return $object->getY() === $this->getY() && $object->getX() === $this->getX();
    }

    /**
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%sx%s', $this->x, $this->y);
    }
}
