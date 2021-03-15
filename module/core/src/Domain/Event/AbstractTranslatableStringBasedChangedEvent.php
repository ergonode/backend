<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\Event;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use JMS\Serializer\Annotation as JMS;

abstract class AbstractTranslatableStringBasedChangedEvent implements AggregateEventInterface
{
    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $to;

    public function __construct(TranslatableString $to)
    {
        $this->to = $to;
    }

    public function getTo(): TranslatableString
    {
        return $this->to;
    }
}
