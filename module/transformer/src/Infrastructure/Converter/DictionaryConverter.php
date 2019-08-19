<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Converter;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\TranslatableCollectionValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class DictionaryConverter implements ConverterInterface
{
    public const TYPE = 'dictionary';

    /**
     * @var array
     *
     * @JMS\Type("array<string, string>")
     */
    private $translations;

    /**
     * @var null|string
     *
     * @JMS\Type("string")
     *
     */
    private $field;

    /**
     * @param array       $translations
     * @param string|null $field
     */
    public function __construct(array $translations = [], ?string $field = null)
    {
        $this->translations = $translations;
        $this->field = $field;
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
     * @param array  $line
     * @param string $field
     *
     * @return ValueInterface
     */
    public function map(array $line, string $field): ValueInterface
    {
        $result = new TranslatableString();
        foreach ($this->translations as $language => $translationField) {
            if (null !== $line[$translationField] && '' !== $line[$translationField]) {
                $result = $result->add(new Language($language), $line[$translationField]);
            }
        }

        $field = $this->field ?: $field;

        return new TranslatableCollectionValue([$line[$field] => $result]);
    }
}
