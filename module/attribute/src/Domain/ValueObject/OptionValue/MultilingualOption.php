<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\ValueObject\OptionValue;

use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use JMS\Serializer\Annotation as JMS;

/**
 */
class MultilingualOption implements OptionInterface
{
    public const TYPE = 'translation';

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $value;

    /**
     * @param TranslatableString $value
     */
    public function __construct(TranslatableString $value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritDoc}
     *
     * @JMS\VirtualProperty()
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return TranslatableString
     */
    public function getValue(): TranslatableString
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function isMultilingual(): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return implode(',', $this->value->getTranslations());
    }

    /**
     * {@inheritDoc}
     */
    public function equal(OptionInterface $value): bool
    {
        return $value instanceof self &&
            array_diff_assoc($value->getValue()->getTranslations(), $this->value->getTranslations()) ===
            array_diff_assoc($this->value->getTranslations(), $value->getValue()->getTranslations());
    }
}
