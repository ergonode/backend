<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Service;

/**
 */
class SHACalculationService implements HashCalculationServiceInterface
{
    /**
     * @param \SplFileInfo $file
     *
     * @return string
     */
    public function calculateHash(\SplFileInfo $file): string
    {
        $result = sha1_file($file->getRealPath());
        if ($result) {
            return $result;
        }

        throw new \RuntimeException(sprintf('Can\'t calculate has for %s file', $file->getFilename()));
    }
}


