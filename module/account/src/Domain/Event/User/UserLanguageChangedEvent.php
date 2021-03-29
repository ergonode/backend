<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Event\User;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;

class UserLanguageChangedEvent implements AggregateEventInterface
{
    private UserId $id;

    private Language $to;

    public function __construct(UserId $id, Language $to)
    {
        $this->id = $id;
        $this->to = $to;
    }

    public function getAggregateId(): UserId
    {
        return $this->id;
    }

    public function getTo(): Language
    {
        return $this->to;
    }
}
