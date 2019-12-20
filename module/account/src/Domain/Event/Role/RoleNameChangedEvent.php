<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Event\Role;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Domain\Event\AbstractStringBasedChangedEvent;
use JMS\Serializer\Annotation as JMS;

/**
 */
class RoleNameChangedEvent extends AbstractStringBasedChangedEvent
{
    /**
     * @var RoleId
     *
     * @JMS\Type("Ergonode\Account\Domain\Entity\RoleId")
     */
    private $id;

    /**
     * @param RoleId $id
     * @param string $from
     * @param string $to
     */
    public function __construct(RoleId $id, string $from, string $to)
    {
        $this->id = $id;
        parent::__construct($from, $to);
    }

    /**
     * @return RoleId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }
}
