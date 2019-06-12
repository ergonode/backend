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
class TranslatableCollectionValue extends AbstractValue implements ValueInterface
{
    public const TYPE = 'translation_collection';

    /**
     * @var TranslatableString[]
     *
     * @JMS\Type("array<Ergonode\Core\Domain\ValueObject\TranslatableString>")
     */
    private $value;

    /**
     * @param TranslatableString[] $value
     */
    public function __construct(array $value)
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
     * @return TranslatableString[]
     */
    public function getValue(): array
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $result = [];
        foreach ($this->value as $value) {
            $result[] = implode(',', $value->getTranslations());
        }

        return implode(',', $result);
    }
}
