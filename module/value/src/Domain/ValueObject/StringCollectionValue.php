<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Domain\ValueObject;

use Ergonode\Core\Domain\ValueObject\Language;

class StringCollectionValue implements ValueInterface
{
    public const TYPE = 'collection';

    /**
     * @var string[]
     */
    private array $value;

    /**
     * @param string[] $value
     */
    public function __construct(array $value)
    {
        $this->value = $value;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return string[]
     */
    public function getValue(): array
    {
        return $this->value;
    }

    public function getTranslation(Language $language): ?string
    {
        return $this->value[$language->getCode()] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return implode(',', $this->value);
    }

    public function isEqual(ValueInterface $value): bool
    {
        return
            $value instanceof self
            && count(array_diff_assoc($value->value, $this->value)) === 0
            && count(array_diff_assoc($this->value, $value->value)) === 0;
    }
}
