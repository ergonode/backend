<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Repository;

use Ergonode\Segment\Domain\Entity\Segment;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;

interface SegmentRepositoryInterface
{
    /**
     * @param SegmentId $id
     *
     * @return Segment|null
     */
    public function load(SegmentId $id): ?Segment;

    /**
     * @param Segment $segment
     */
    public function save(Segment $segment): void;

    /**
     * @param SegmentId $id
     *
     * @return bool
     */
    public function exists(SegmentId $id): bool;

    /**
     * @param Segment $segment
     */
    public function delete(Segment $segment): void;
}
