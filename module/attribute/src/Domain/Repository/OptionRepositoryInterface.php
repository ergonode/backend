<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Repository;

use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\SharedKernel\Domain\AggregateId;

interface OptionRepositoryInterface
{
    public function load(AggregateId $id): ?AbstractOption;

    public function save(AbstractOption $option): void;

    public function delete(AbstractOption $option): void;
}
