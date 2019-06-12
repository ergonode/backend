<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributeImage\Infrastructure\Provider;

use Ergonode\AttributeImage\Domain\ValueObject\ImageFormat;

/**
 */
class ImageFormatProvider
{
    /**
     * @return array
     */
    public function dictionary(): array
    {
        $result = [];
        foreach (ImageFormat::AVAILABLE as $label) {
            $result[$label] = ucfirst($label);
        }

        return $result;
    }
}
