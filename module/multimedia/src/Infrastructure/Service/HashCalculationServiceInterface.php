<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Service;

use Ergonode\Multimedia\Domain\ValueObject\Hash;

interface HashCalculationServiceInterface
{
    /**
     * @param \SplFileInfo $file
     *
     * @return Hash
     */
    public function calculateHash(\SplFileInfo $file): Hash;
}
