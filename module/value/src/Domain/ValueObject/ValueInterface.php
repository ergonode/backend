<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Domain\ValueObject;

use Ergonode\Core\Domain\ValueObject\Language;

interface ValueInterface
{
    public const NAMESPACE = 'cb2600df-94fb-4755-9e6a-a15591a8e510';

    /**
     * @return string[]
     */
    public function getValue(): array;

    public function getType(): string;

    public function getTranslation(Language $language): ?string;

    public function hasTranslation(Language $language): bool;

    public function __toString(): string;

    public function merge(ValueInterface $value): self;

    public function isEqual(ValueInterface $value): bool;
}
