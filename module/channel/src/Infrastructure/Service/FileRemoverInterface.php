<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Service;

use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

interface FileRemoverInterface
{
    public function remove(ExportId $fileName): bool;
}
