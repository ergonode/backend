<?php

/**
 * Copyright © Ergonaut Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Domain\ValueObject;

/**
 */
interface ValueInterface
{
    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function __toString(): string;
}
