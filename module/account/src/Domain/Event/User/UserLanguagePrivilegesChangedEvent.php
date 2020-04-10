<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Event\User;

use Ergonode\Account\Domain\ValueObject\LanguagePrivilege;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

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
     * @var LanguagePrivilege[]
     *
     * @JMS\Type("array<Ergonode\Account\Domain\ValueObject\LanguagePrivilege>")
     */
    private array $from;

    /**
     * @var LanguagePrivilege[]
     *
     * @JMS\Type("array<Ergonode\Account\Domain\ValueObject\LanguagePrivilege>")
     */
    private array $to;

    /**
     * @param UserId              $id
     * @param LanguagePrivilege[] $from
     * @param LanguagePrivilege[] $to
     */
    public function __construct(UserId $id, array $from, array $to)
    {
        Assert::allIsInstanceOf($from, LanguagePrivilege::class);
        Assert::allIsInstanceOf($to, LanguagePrivilege::class);

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
     * @return LanguagePrivilege[]
     */
    public function getFrom(): array
    {
        return $this->from;
    }

    /**
     * @return LanguagePrivilege[]
     */
    public function getTo(): array
    {
        return $this->to;
    }
}
