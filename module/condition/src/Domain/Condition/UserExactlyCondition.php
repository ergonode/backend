<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Condition\Domain\ConditionInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class UserExactlyCondition implements ConditionInterface
{
    public const TYPE = 'USER_EXACTLY_CONDITION';
    public const PHRASE = 'USER_EXACTLY_CONDITION_PHRASE';

    /**
     * @var UserId
     *
     * @JMS\Type("Ergonode\Account\Domain\Entity\UserId")
     */
    private UserId $user;

    /**
     * @param UserId $user
     */
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

    /**
     * @return UserId
     */
    public function getUser(): UserId
    {
        return $this->user;
    }
}
