<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Domain\ValueObject;

/**
 */
interface ValueInterface
{
    public const NAMESPACE = 'cb2600df-94fb-4755-9e6a-a15591a8e510';

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
