<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Event\User;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Domain\Event\AbstractStringBasedChangedEvent;
use JMS\Serializer\Annotation as JMS;

/**
 */
class UserLastNameChangedEvent extends AbstractStringBasedChangedEvent
{
    /**
     * @var UserId
     *
     * @JMS\Type("Ergonode\Account\Domain\Entity\UserId")
     */
    private $id;

    /**
     * @param UserId $id
     * @param string $from
     * @param string $to
     */
    public function __construct(UserId $id, string $from, string $to)
    {
        $this->id = $id;
        parent::__construct($from, $to);
    }

    /**
     * @return UserId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }
}
