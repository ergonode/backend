<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Event\Role;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use JMS\Serializer\Annotation as JMS;

/**
 */
class RoleDeletedEvent extends AbstractDeleteEvent
{
    /**
     * @var RoleId
     *
     * @JMS\Type("Ergonode\Account\Domain\Entity\RoleId")
     */
    private $id;

    /**
     * @param RoleId $id
     */
    public function __construct(RoleId $id)
    {
        $this->id = $id;
    }

    /**
     * @return RoleId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }
}
