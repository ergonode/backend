<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

interface MultimediaTypeQueryInterface
{
    public function findMultimediaType(MultimediaId $id): ?string;
}
