<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Event\User;

use Ergonode\Account\Domain\ValueObject\LanguagePrivileges;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use JMS\Serializer\Annotation as JMS;

class UserLanguagePrivilegesCollectionChangedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UserId")
     */
    private UserId $id;

    /**
     * @var LanguagePrivileges[]
     *
     * @JMS\Type("array<string, Ergonode\Account\Domain\ValueObject\LanguagePrivileges>")
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
