<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Domain\ValueObject;

interface ValueInterface
{
    public const NAMESPACE = 'cb2600df-94fb-4755-9e6a-a15591a8e510';

    /**
     * @return array
     */
    public function getValue(): array;

    public function getType(): string;

    public function __toString(): string;

    public function isEqual(ValueInterface $value): bool;
}
