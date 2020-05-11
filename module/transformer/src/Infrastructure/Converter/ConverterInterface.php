<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Converter;

/**
 */
interface ConverterInterface
{
    /**
     * @return string
     */
    public function getType(): string;
}
