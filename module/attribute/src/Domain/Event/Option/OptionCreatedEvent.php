<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Event\Option;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class OptionCreatedEvent implements AggregateEventInterface
{
    private AggregateId $id;

    private AttributeId $attributeId;

    private OptionKey $code;

    private TranslatableString $label;

    public function __construct(AggregateId $id, AttributeId $attributeId, OptionKey $code, TranslatableString $label)
    {
        $this->id = $id;
        $this->attributeId = $attributeId;
        $this->code = $code;
        $this->label = $label;
    }

    public function getAggregateId(): AggregateId
    {
        return $this->id;
    }

    public function getAttributeId(): AttributeId
    {
        return $this->attributeId;
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
