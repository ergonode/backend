<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;

class TransformerDeletedEvent extends AbstractDeleteEvent
{
    private TransformerId $id;

    public function __construct(TransformerId $id)
    {
        $this->id = $id;
    }

    public function getAggregateId(): TransformerId
    {
        return $this->id;
    }
}
