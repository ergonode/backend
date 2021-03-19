<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Domain\Event;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\AggregateId;

class MultimediaNameChangedEvent implements AggregateEventInterface
{
    private MultimediaId $id;

    private string $name;

    public function __construct(MultimediaId $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getAggregateId(): AggregateId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
