<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Converter\Mapper;

use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Transformer\Infrastructure\Converter\MultilingualTextConverter;

/**
 */
class MultilingualTextConverterMapper implements ConverterMapperInterface
{
    /**
     * @param ConverterInterface $converter
     *
     * @return bool
     */
    public function supported(ConverterInterface $converter): bool
    {
        return $converter instanceof MultilingualTextConverter;
    }

    /**
     * @param ConverterInterface|MultilingualTextConverter $converter
     * @param array                            $line
     * @param string|null                      $default
     *
     * @return ValueInterface|null
     */
    public function map(ConverterInterface $converter, array $line, ?string $default = null): ValueInterface
    {
        $fields = $converter->getFields();

        $result = [];
        foreach ($fields as $language => $field) {
            $result[$language] = $line[$field];
        }

        return new TranslatableStringValue(new TranslatableString($result));
    }
}
