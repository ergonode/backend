<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Event\Role;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Account\Domain\ValueObject\Privilege;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class RolePrivilegesChangedEvent implements DomainEventInterface
{
    /**
     * @var RoleId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\RoleId")
     */
    private RoleId $id;

    /**
     * @var Privilege[]
     *
     * @JMS\Type("array<Ergonode\Account\Domain\ValueObject\Privilege>")
     */
    private array $from;

    /**
     * @var Privilege[]
     *
     * @JMS\Type("array<Ergonode\Account\Domain\ValueObject\Privilege>")
     */
    private array $to;

    /**
     * @param RoleId      $id
     * @param Privilege[] $from
     * @param Privilege[] $to
     */
    public function __construct(RoleId $id, array $from, array $to)
    {
        Assert::allIsInstanceOf($from, Privilege::class);
        Assert::allIsInstanceOf($to, Privilege::class);

        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return RoleId
     */
    public function getAggregateId(): RoleId
    {
        return $this->id;
    }

    /**
     * @return Privilege[]
     */
    public function getFrom(): array
    {
        return $this->from;
    }

    /**
     * @return Privilege[]
     */
    public function getTo(): array
    {
        return $this->to;
    }
}
