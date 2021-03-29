<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Event\User;

use Ergonode\Core\Domain\ValueObject\LanguagePrivileges;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

class UserLanguagePrivilegesCollectionChangedEvent implements AggregateEventInterface
{
    private UserId $id;

    /**
     * @var LanguagePrivileges[]
     */
    private array $to;

    /**
     * @param LanguagePrivileges[] $to
     */
    public function __construct(UserId $id, array $to)
    {

        $this->id = $id;
        $this->to = $to;
    }

    public function getAggregateId(): UserId
    {
        return $this->id;
    }

    /**
     * @return LanguagePrivileges[]
     */
    public function getTo(): array
    {
        return $this->to;
    }
}
