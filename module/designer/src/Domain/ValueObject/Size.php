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
class Size
{
    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @param int $width
     * @param int $height
     */
    public function __construct(int $width, int $height)
    {
        Assert::greaterThanEq($width, 0);
        Assert::greaterThanEq($height, 0);

        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @param Size $object
     *
     * @return bool
     */
    public function isEqual(Size $object): bool
    {
        return $object->getWidth() === $this->getWidth() && $object->getHeight() === $this->getHeight();
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }
}
