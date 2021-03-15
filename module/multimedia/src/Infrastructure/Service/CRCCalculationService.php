<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Service;

use Ergonode\Multimedia\Domain\ValueObject\Hash;

class CRCCalculationService implements HashCalculationServiceInterface
{
    public function calculateHash(\SplFileInfo $file): Hash
    {
        return new Hash(hash_file('crc32b', $file->getRealPath()));
    }
}
