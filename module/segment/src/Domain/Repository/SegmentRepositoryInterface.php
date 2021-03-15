<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Domain\Repository;

use Ergonode\Segment\Domain\Entity\Segment;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;

interface SegmentRepositoryInterface
{
    public function load(SegmentId $id): ?Segment;

    public function save(Segment $segment): void;

    public function exists(SegmentId $id): bool;

    public function delete(Segment $segment): void;
}
