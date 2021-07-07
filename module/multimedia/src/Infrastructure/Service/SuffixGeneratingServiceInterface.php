<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Service;

interface SuffixGeneratingServiceInterface
{
    public function generateSuffix(string $name, int $iterationIndex): string;
}
