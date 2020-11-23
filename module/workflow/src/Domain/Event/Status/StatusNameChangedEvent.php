<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Event\Status;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\Event\AbstractTranslatableStringBasedChangedEvent;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use JMS\Serializer\Annotation as JMS;

class StatusNameChangedEvent extends AbstractTranslatableStringBasedChangedEvent
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\StatusId")
     */
    private StatusId $id;

    public function __construct(StatusId $id, TranslatableString $to)
    {
        parent::__construct($to);
        $this->id = $id;
    }

    public function getAggregateId(): StatusId
    {
        return $this->id;
    }
}
