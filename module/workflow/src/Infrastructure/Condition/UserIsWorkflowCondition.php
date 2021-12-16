<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Condition;

use Ergonode\Workflow\Domain\Condition\WorkflowConditionInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

class UserIsWorkflowCondition implements WorkflowConditionInterface
{
    public const TYPE = 'USER_IS_CONDITION';
    public const PHRASE = 'USER_IS_CONDITION_PHRASE';

    private UserId $userId;

    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
