<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Condition\Domain\ConditionInterface;

class RoleExactlyCondition implements ConditionInterface
{
    public const TYPE = 'ROLE_EXACTLY_CONDITION';
    public const PHRASE = 'ROLE_EXACTLY_CONDITION_PHRASE';

    private RoleId $role;

    public function __construct(RoleId $role)
    {
        $this->role = $role;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getRole(): RoleId
    {
        return $this->role;
    }
}
