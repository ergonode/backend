<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Converter;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Transformer\Infrastructure\Exception\ConverterException;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TranslationConverter implements ConverterInterface
{
    public const TYPE = 'translation';

    /**
     * @var array
     *
     * @JMS\Type("array<string, string>")
     */
    private $translations;

    /**
     * @param string[] $translations
     */
    public function __construct(array $translations = [])
    {
        $this->translations = $translations;
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
     *
     * @throws ConverterException
     */
    public function map(array $line, string $field): ValueInterface
    {
        $result = [];
        foreach ($this->translations as $language => $translationField) {
            if (isset($line[$translationField]) && null !== $line[$translationField]) {
                if (!Language::isValid($language)) {
                    throw new ConverterException(sprintf('"%s is invalid language code', $language));
                }

                $result[$language] = $line[$translationField];
            }
        }

        return new TranslatableStringValue(new TranslatableString($result));
    }
}
