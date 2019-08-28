<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Event\User;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;

/**
 */
class UserDeactivatedEvent implements DomainEventInterface
{
    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return false;
    }
}
