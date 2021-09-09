<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Domain\ValueObject;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Core\Domain\ValueObject\Language;
use Webmozart\Assert\Assert;

class TranslatableStringValue implements ValueInterface
{
    public const TYPE = 'translation';

    private TranslatableString $value;

    public function __construct(TranslatableString $value)
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
        return $this->value->getTranslations();
    }

    public function getTranslation(Language $language): ?string
    {
        return $this->value->get($language);
    }

    public function merge(ValueInterface $value): self
    {
        Assert::isInstanceOf($value, self::class);

        return new self(new TranslatableString(array_merge($this->value->getTranslations(), $value->getValue())));
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return implode(',', $this->value->getTranslations());
    }

    public function isEqual(ValueInterface $value): bool
    {
        return
            $value instanceof self
            && $value->value->isEqual($this->value);
    }
}
