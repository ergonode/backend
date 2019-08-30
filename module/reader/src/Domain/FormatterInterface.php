<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Domain;

/**
 */
interface FormatterInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param string $string
     *
     * @return string
     */
    public function format(string $string): string;
}
