<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Event\Role;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Account\Domain\ValueObject\Privilege;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

class RolePrivilegesChangedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\RoleId")
     */
    private RoleId $id;

    /**
     * @var Privilege[]
     *
     * @JMS\Type("array<Ergonode\Account\Domain\ValueObject\Privilege>")
     */
    private array $to;

    public function __construct(RoleId $id, array $to)
    {
        Assert::allIsInstanceOf($to, Privilege::class);

        $this->id = $id;
        $this->to = $to;
    }

    public function getAggregateId(): RoleId
    {
        return $this->id;
    }

    /**
     * @return Privilege[]
     */
    public function getTo(): array
    {
        return $this->to;
    }
}
