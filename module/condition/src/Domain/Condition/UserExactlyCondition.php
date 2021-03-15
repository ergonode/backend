<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Condition\Domain\ConditionInterface;
use JMS\Serializer\Annotation as JMS;

class UserExactlyCondition implements ConditionInterface
{
    public const TYPE = 'USER_EXACTLY_CONDITION';
    public const PHRASE = 'USER_EXACTLY_CONDITION_PHRASE';

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UserId")
     */
    private UserId $user;

    public function __construct(UserId $user)
    {
        $this->user = $user;
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

    public function getUser(): UserId
    {
        return $this->user;
    }
}
