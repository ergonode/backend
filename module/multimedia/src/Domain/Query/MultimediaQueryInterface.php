<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Query;

use Ergonode\Multimedia\Domain\ValueObject\Hash;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Grid\DataSetInterface;

interface MultimediaQueryInterface
{
    public function fileExists(Hash $hash): bool;

    public function findIdByHash(Hash $hash): ?MultimediaId;

    public function findIdByFilename(string $filename): ?MultimediaId;

    /**
     * @return array
     */
    public function getAll(): array;

    public function getDataSet(): DataSetInterface;

    /**
     * @return string[]
     */
    public function getTypes(): array;
}
