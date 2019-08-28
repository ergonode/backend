<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\ValueObject;

/**
 */
interface OptionInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @return bool
     */
    public function isMultilingual(): bool;

    /**
     * @param OptionInterface $value
     *
     * @return bool
     */
    public function equal(OptionInterface $value): bool;
}
