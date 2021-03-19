<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Domain\Event;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

class MultimediaDeletedEvent implements AggregateEventInterface
{
    private MultimediaId $id;

    public function __construct(MultimediaId $id)
    {
        $this->id = $id;
    }

    public function getAggregateId(): MultimediaId
    {
        return $this->id;
    }
}
