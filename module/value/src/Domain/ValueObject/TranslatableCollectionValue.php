<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Domain\ValueObject;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class TranslatableCollectionValue implements ValueInterface
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
        Assert::allIsInstanceOf($value, TranslatableString::class);

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
     * @return TranslatableString[]
     */
    public function getValue(): array
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
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
