<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Event\User;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class UserDeactivatedEvent implements DomainEventInterface
{
    /**
     * @var UserId
     *
     * @JMS\Type("Ergonode\Account\Domain\Entity\UserId")
     */
    private $id;

    /**
     * @param UserId $id
     */
    public function __construct(UserId $id)
    {
        $this->id = $id;
    }

    /**
     * @return UserId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }
}
