<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Event\Option;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;

class OptionCodeChangedEvent implements AggregateEventInterface
{
    private AggregateId $id;

    private OptionKey $code;

    public function __construct(AggregateId $id, OptionKey $code)
    {
        $this->id = $id;
        $this->code = $code;
    }

    public function getAggregateId(): AggregateId
    {
        return $this->id;
    }

    public function getCode(): OptionKey
    {
        return $this->code;
    }
}
