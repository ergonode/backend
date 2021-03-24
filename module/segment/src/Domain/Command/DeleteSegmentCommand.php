<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;

class DeleteSegmentCommand implements SegmentCommandInterface
{
    private SegmentId $id;

    public function __construct(SegmentId $id)
    {
        $this->id = $id;
    }

    public function getId(): SegmentId
    {
        return $this->id;
    }
}
