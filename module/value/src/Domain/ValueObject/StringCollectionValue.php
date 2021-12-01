<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Domain\ValueObject;

use Ergonode\Core\Domain\ValueObject\Language;
use Webmozart\Assert\Assert;

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

    public function hasTranslation(Language $language): bool
    {
        return array_key_exists($language->getCode(), $this->value);
    }

    public function merge(ValueInterface $value): self
    {
        Assert::isInstanceOf($value, self::class);

        return new self(array_merge($this->value, $value->getValue()));
    }

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
