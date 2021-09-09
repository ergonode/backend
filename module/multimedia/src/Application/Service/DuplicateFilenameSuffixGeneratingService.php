<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Application\Service;

class DuplicateFilenameSuffixGeneratingService implements SuffixGeneratingServiceInterface
{
    private const MAX_LENGTH = 128;

    public function generateSuffix(string $name, int $iterationIndex): string
    {
        $suffix = '('.$iterationIndex.')';
        if (mb_strlen($name) > (self::MAX_LENGTH - mb_strlen($suffix))) {
            return mb_substr($name, 0, self::MAX_LENGTH - mb_strlen($suffix)).$suffix;
        }

        return $name.$suffix;
    }
}
