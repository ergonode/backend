<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Transformer\Infrastructure\Generator;

/**
 */
interface TransformerGeneratorStrategyInterface
{
    /**
     * @return string
     */
    public function getType(): string;
}
