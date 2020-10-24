<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Event\User;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;

use Ergonode\EventSourcing\Domain\Event\AbstractStringBasedChangedEvent;
use JMS\Serializer\Annotation as JMS;

class UserFirstNameChangedEvent extends AbstractStringBasedChangedEvent
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UserId")
     */
    private UserId $id;

    public function __construct(UserId $id, string $from, string $to)
    {
        $this->id = $id;
        parent::__construct($from, $to);
    }

    public function getAggregateId(): UserId
    {
        return $this->id;
    }
}
