<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\SharedKernel\Domain\Aggregate\TransitionId;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class Transition
{
    private TransitionId $id;

    private StatusId $from;

    private StatusId $to;

    private ?ConditionSetId $conditionSetId;

    /**
     * @var RoleId[]
     */
    private array $roleIds;

    /**
     * @param RoleId[] $roleIds
     */
    public function __construct(
        TransitionId $id,
        StatusId $from,
        StatusId $to,
        array $roleIds = [],
        ?ConditionSetId $conditionSetId = null
    ) {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
        $this->conditionSetId = $conditionSetId;
        $this->roleIds = $roleIds;
    }

    public function getId(): TransitionId
    {
        return $this->id;
    }

    public function getFrom(): StatusId
    {
        return $this->from;
    }

    public function getTo(): StatusId
    {
        return $this->to;
    }

    /**
     * @return RoleId[]
     */
    public function getRoleIds(): array
    {
        return $this->roleIds;
    }

    public function getConditionSetId(): ?ConditionSetId
    {
        return $this->conditionSetId;
    }

    /**
     * @throws \Exception
     */
    public function changeConditionSetId(?ConditionSetId $conditionSetId = null): void
    {
        $this->conditionSetId = $conditionSetId;
    }

    /**
     * @param array $roleIds
     *
     * @throws \Exception
     */
    public function changeRoleIds(array $roleIds = []): void
    {
        Assert::allIsInstanceOf($roleIds, RoleId::class);

        $this->roleIds = $roleIds;
    }
}
