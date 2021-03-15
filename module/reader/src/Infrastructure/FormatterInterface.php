<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Reader\Infrastructure;

interface FormatterInterface
{
    public function getType(): string;

    public function format(string $string): string;
}
