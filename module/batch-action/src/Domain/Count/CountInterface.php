<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Count;

use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilterInterface;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;

interface CountInterface
{
    public function supports(BatchActionType $type): bool;
    public function count(BatchActionType $type, BatchActionFilterInterface $filter): int;
}
