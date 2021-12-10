<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Event\Option;

use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\AggregateId;

class OptionCreatedEvent implements AggregateEventInterface
{
    private AggregateId $id;

    private OptionKey $code;

    private TranslatableString $label;

    public function __construct(AggregateId $id, OptionKey $code, TranslatableString $label)
    {
        $this->id = $id;
        $this->code = $code;
        $this->label = $label;
    }

    public function getAggregateId(): AggregateId
    {
        return $this->id;
    }

    public function getCode(): OptionKey
    {
        return $this->code;
    }

    public function getLabel(): TranslatableString
    {
        return $this->label;
    }
}
