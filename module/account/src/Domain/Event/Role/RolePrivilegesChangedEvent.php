<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Event\Role;

use Ergonode\Account\Domain\ValueObject\Privilege;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class RolePrivilegesChangedEvent implements DomainEventInterface
{
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
     * @param Privilege[] $from
     * @param Privilege[] $to
     */
    public function __construct(array $from, array $to)
    {
        Assert::allIsInstanceOf($from, Privilege::class);
        Assert::allIsInstanceOf($to, Privilege::class);

        $this->from = $from;
        $this->to = $to;
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
