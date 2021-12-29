<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Provider;

use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilterInterface;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\SharedKernel\Domain\AggregateId;

interface BatchActionFilterIdsInterface
{
    public function supports(BatchActionType $type): bool;

    /**
     * @return AggregateId []
     */
    public function filter(BatchActionFilterInterface $filter): array;
}
