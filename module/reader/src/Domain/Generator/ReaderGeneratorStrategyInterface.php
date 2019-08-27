<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Reader\Domain\Generator;

use Ergonode\Reader\Domain\Entity\Reader;

/**
 */
interface ReaderGeneratorStrategyInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return Reader
     */
    public function generate(): Reader;
}
