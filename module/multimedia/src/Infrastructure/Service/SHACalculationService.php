<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Service;

use Ergonode\Multimedia\Domain\ValueObject\Hash;

class SHACalculationService implements HashCalculationServiceInterface
{
    public function calculateHash(\SplFileInfo $file): Hash
    {
        $result = sha1_file($file->getRealPath());
        if ($result) {
            return new Hash($result);
        }

        throw new \RuntimeException(sprintf('Can\'t calculate has for %s file', $file->getFilename()));
    }
}
