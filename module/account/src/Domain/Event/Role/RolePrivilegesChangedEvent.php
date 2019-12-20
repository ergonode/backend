<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Event\Role;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Account\Domain\ValueObject\Privilege;
use Ergonode\Core\Domain\Entity\AbstractId;
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
     * @JMS\Type("Ergonode\Account\Domain\Entity\RoleId")
     */
    private $id;

    /**
     * @var Privilege[]
     *
     * @JMS\Type("array<Ergonode\Account\Domain\ValueObject\Privilege>")
     */
    private $from;

    /**
     * @var Privilege[]
     *
     * @JMS\Type("array<Ergonode\Account\Domain\ValueObject\Privilege>")
     */
    private $to;

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
    public function getAggregateId(): AbstractId
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
