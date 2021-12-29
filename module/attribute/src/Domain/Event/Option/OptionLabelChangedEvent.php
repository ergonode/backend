<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Event\Option;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class OptionLabelChangedEvent implements AggregateEventInterface
{
    private AggregateId $id;

    private TranslatableString $to;

    public function __construct(AggregateId $id, TranslatableString $to)
    {
        $this->id = $id;
        $this->to = $to;
    }

    public function getAggregateId(): AggregateId
    {
        return $this->id;
    }

    public function getTo(): TranslatableString
    {
        return $this->to;
    }
}
