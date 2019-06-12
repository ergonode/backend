<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Service;

/**
 * Class CRCCalculationService
 */
class CRCCalculationService implements HashCalculationServiceInterface
{
    /**
     * @param \SplFileInfo $file
     *
     * @return string
     */
    public function calculateHash(\SplFileInfo $file): string
    {
        return hash_file('crc32b', $file->getRealPath());
    }
}
