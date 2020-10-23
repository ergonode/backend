<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Event\Role;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;

use Ergonode\EventSourcing\Domain\Event\AbstractStringBasedChangedEvent;
use JMS\Serializer\Annotation as JMS;

class RoleDescriptionChangedEvent extends AbstractStringBasedChangedEvent
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\RoleId")
     */
    private RoleId $id;

    public function __construct(RoleId $id, ?string $from, ?string $to)
    {
        $this->id = $id;
        parent::__construct($from, $to);
    }

    public function getAggregateId(): RoleId
    {
        return $this->id;
    }
}
