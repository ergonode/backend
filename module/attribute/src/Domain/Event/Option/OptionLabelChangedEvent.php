<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Event\Option;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use JMS\Serializer\Annotation as JMS;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class OptionLabelChangedEvent implements AggregateEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\AggregateId")
     */
    private AggregateId $id;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
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
