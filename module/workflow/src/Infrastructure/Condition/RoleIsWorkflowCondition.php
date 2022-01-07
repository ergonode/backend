<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Condition;

use Ergonode\Workflow\Domain\Condition\WorkflowConditionInterface;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;

class RoleIsWorkflowCondition implements WorkflowConditionInterface
{
    public const TYPE = 'ROLE_IS_CONDITION';
    public const PHRASE = 'ROLE_IS_CONDITION_PHRASE';

    private RoleId $role;

    public function __construct(RoleId $role)
    {
        $this->role = $role;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getRoleId(): RoleId
    {
        return $this->role;
    }
}
