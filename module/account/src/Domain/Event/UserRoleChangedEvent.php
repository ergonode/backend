<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Event;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class UserRoleChangedEvent implements DomainEventInterface
{
    /**
     * @var RoleId
     *
     * @JMS\Type("Ergonode\Account\Domain\Entity\RoleId")
     */
    private $from;

    /**
     * @var RoleId
     *
     * @JMS\Type("Ergonode\Account\Domain\Entity\RoleId")
     */
    private $to;

    /**
     * @param RoleId $from
     * @param RoleId $to
     */
    public function __construct(RoleId $from, RoleId $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return RoleId
     */
    public function getFrom(): RoleId
    {
        return $this->from;
    }

    /**
     * @return RoleId
     */
    public function getTo(): RoleId
    {
        return $this->to;
    }
}
