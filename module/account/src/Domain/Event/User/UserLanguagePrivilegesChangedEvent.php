<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Event\User;

use Ergonode\Account\Domain\ValueObject\LanguagePrivileges;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class UserLanguagePrivilegesChangedEvent implements DomainEventInterface
{
    /**
     * @var UserId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UserId")
     */
    private UserId $id;

    /**
     * @var LanguagePrivileges
     *
     * @JMS\Type("Ergonode\Account\Domain\ValueObject\LanguagePrivileges")
     */
    private LanguagePrivileges $from;

    /**
     * @var LanguagePrivileges
     *
     * @JMS\Type("Ergonode\Account\Domain\ValueObject\LanguagePrivileges")
     */
    private LanguagePrivileges $to;

    /**
     * @param UserId             $id
     * @param LanguagePrivileges $from
     * @param LanguagePrivileges $to
     */
    public function __construct(UserId $id, LanguagePrivileges $from, LanguagePrivileges $to)
    {

        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return UserId
     */
    public function getAggregateId(): UserId
    {
        return $this->id;
    }

    /**
     * @return LanguagePrivileges
     */
    public function getFrom(): LanguagePrivileges
    {
        return $this->from;
    }

    /**
     * @return LanguagePrivileges
     */
    public function getTo(): LanguagePrivileges
    {
        return $this->to;
    }
}
