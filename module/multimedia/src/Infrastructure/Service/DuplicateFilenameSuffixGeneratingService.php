<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Service;

class DuplicateFilenameSuffixGeneratingService implements SuffixGeneratingServiceInterface
{
    private const MAX_LENGTH = 128;

    public function generateSuffix(string $name, int $iterationIndex): string
    {
        $suffix = '('.$iterationIndex.')';
        if (strlen($name) > (self::MAX_LENGTH - strlen($suffix))) {
            return substr($name, 0, self::MAX_LENGTH - strlen($suffix)).$suffix;
        }

        return $name.$suffix;
    }
}
