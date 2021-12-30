<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\ValueObject;

interface OptionInterface
{
    /**
     * @return mixed
     */
    public function getValue();

    public function __toString(): string;

    public function isMultilingual(): bool;

    public function equal(OptionInterface $value): bool;
}
