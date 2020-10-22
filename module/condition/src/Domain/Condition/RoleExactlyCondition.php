<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Condition\Domain\ConditionInterface;
use JMS\Serializer\Annotation as JMS;

class RoleExactlyCondition implements ConditionInterface
{
    public const TYPE = 'ROLE_EXACTLY_CONDITION';
    public const PHRASE = 'ROLE_EXACTLY_CONDITION_PHRASE';

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\RoleId")
     */
    private RoleId $role;

    public function __construct(RoleId $role)
    {
        $this->role = $role;
    }

    /**
     * {@inheritDoc}
     *
     * @JMS\VirtualProperty()
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    public function getRole(): RoleId
    {
        return $this->role;
    }
}
