<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Status;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\Event\AbstractTranslatableStringBasedChangedEvent;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class StatusNameChangedEvent extends AbstractTranslatableStringBasedChangedEvent
{
    /**
     * @var StatusId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\StatusId")
     */
    private StatusId $id;

    /**
     * @param StatusId           $id
     * @param TranslatableString $from
     * @param TranslatableString $to
     */
    public function __construct(StatusId $id, TranslatableString $from, TranslatableString $to)
    {
        parent::__construct($from, $to);
        $this->id = $id;
    }

    /**
     * @return StatusId
     */
    public function getAggregateId(): StatusId
    {
        return $this->id;
    }
}
