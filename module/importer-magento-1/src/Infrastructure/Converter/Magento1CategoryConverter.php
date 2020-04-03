<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Converter;

use JMS\Serializer\Annotation as JMS;
use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;

/**
 */
class Magento1CategoryConverter implements ConverterInterface
{
    public const TYPE = 'magento-1-category-converter';
    public const ROOT = '_category_root';
    public const CATEGORY = '_category';

    /**
     * {@inheritDoc}
     *
     * @JMS\VirtualProperty()
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
