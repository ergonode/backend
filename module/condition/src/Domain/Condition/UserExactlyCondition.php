<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Condition\Domain\ConditionInterface;

class UserExactlyCondition implements ConditionInterface
{
    public const TYPE = 'USER_EXACTLY_CONDITION';
    public const PHRASE = 'USER_EXACTLY_CONDITION_PHRASE';

    private UserId $user;

    public function __construct(UserId $user)
    {
        $this->user = $user;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getUser(): UserId
    {
        return $this->user;
    }
}
