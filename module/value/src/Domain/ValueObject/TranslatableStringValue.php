<?php

/**
 * Copyright © Ergonaut Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Domain\ValueObject;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TranslatableStringValue extends AbstractValue implements ValueInterface
{
    public const TYPE = 'translation';

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $value;

    /**
     * @param TranslatableString $value
     */
    public function __construct(TranslatableString $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
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
     * @return string
     */
    public function __toString(): string
    {
        return implode(',', $this->value->getTranslations());
    }
}
