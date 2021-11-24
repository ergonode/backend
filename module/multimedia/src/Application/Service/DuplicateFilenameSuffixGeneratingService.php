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

    public function generateSuffix(string $filename, string $extension, int $iterationIndex): string
    {
        $name = $filename;
        $extensionToAppend = '';
        if (!empty($extension) && str_ends_with($filename, $extension)) {
            $extensionToAppend = '.'.$extension;
            $name = substr($filename, 0, -(strlen($extension) + 1));
        }
        $suffix = '('.$iterationIndex.')';
        if (mb_strlen($filename) > (self::MAX_LENGTH - mb_strlen($suffix))) {
            return mb_substr(
                $name,
                0,
                self::MAX_LENGTH - mb_strlen($suffix)-mb_strlen($extensionToAppend)
            ).$suffix.$extensionToAppend;
        }

        return $name.$suffix.$extensionToAppend;
    }
}
