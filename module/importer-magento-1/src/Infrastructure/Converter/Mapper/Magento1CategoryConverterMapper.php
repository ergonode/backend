<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Converter\Mapper;

use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Transformer\Infrastructure\Converter\JoinConverter;
use Ergonode\Transformer\Infrastructure\Converter\Mapper\ConverterMapperInterface;
use Ergonode\ImporterMagento1\Infrastructure\Converter\Magento1CategoryConverter;

/**
 */
class Magento1CategoryConverterMapper implements ConverterMapperInterface
{
    /**
     * @param ConverterInterface $converter
     *
     * @return bool
     */
    public function supported(ConverterInterface $converter): bool
    {
        return $converter instanceof Magento1CategoryConverter;
    }

    /**
     * @param ConverterInterface|JoinConverter $converter
     * @param array                            $line
     * @param string|null                      $default
     *
     * @return string|null
     */
    public function map(ConverterInterface $converter, array $line, ?string $default = null): ?string
    {
        $category = $line[Magento1CategoryConverter::CATEGORY];

        if (array_key_exists(Magento1CategoryConverter::ROOT, $line)) {
            $category = sprintf('%s/%s', $line[Magento1CategoryConverter::ROOT], $category);
        }

        return $category;
    }
}
