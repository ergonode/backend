<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Event\Status;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Core\Domain\Event\AbstractTranslatableStringBasedChangedEvent;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class StatusDescriptionChangedEvent extends AbstractTranslatableStringBasedChangedEvent
{
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
