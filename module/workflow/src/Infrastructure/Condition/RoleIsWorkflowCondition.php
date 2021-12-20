<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
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

    private RoleId $roleId;

    public function __construct(RoleId $roleId)
    {
        $this->roleId = $roleId;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getRoleId(): RoleId
    {
        return $this->roleId;
    }
}