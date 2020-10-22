<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\EventSourcing\Domain\AbstractEntity;
use Ergonode\SharedKernel\Domain\Aggregate\TransitionId;
use Ergonode\Workflow\Domain\Event\Transition\TransitionConditionSetChangedEvent;
use Ergonode\Workflow\Domain\Event\Transition\TransitionRoleIdsChangedEvent;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class Transition extends AbstractEntity
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TransitionId")
     */
    private TransitionId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\StatusId")
     */
    private StatusId $from;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\StatusId")
     */
    private StatusId $to;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId")
     */
    private ?ConditionSetId $conditionSetId;

    /**
     * @var RoleId[]
     *
     * @JMS\Type("array<Ergonode\SharedKernel\Domain\Aggregate\RoleId>")
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
        if (null === $conditionSetId && null === $this->conditionSetId) {
            return;
        }

        if (null !== $conditionSetId &&
            null !==  $this->conditionSetId &&
            $conditionSetId->isEqual($this->conditionSetId)
        ) {
            return;
        }

        $this->apply(new TransitionConditionSetChangedEvent($this->aggregateRoot->getId(), $this->id, $conditionSetId));
    }

    /**
     * @param array $roleIds
     *
     * @throws \Exception
     */
    public function changeRoleIds(array $roleIds = []): void
    {
        Assert::allIsInstanceOf($roleIds, RoleId::class);

        $this->apply(new TransitionRoleIdsChangedEvent($this->aggregateRoot->getId(), $this->id, $roleIds));
    }

    protected function applyTransitionConditionSetChangedEvent(TransitionConditionSetChangedEvent $event): void
    {
        if ($this->id->isEqual($event->getTransitionId())) {
            $this->conditionSetId = $event->getConditionSetId();
        }
    }

    protected function applyTransitionRoleIdsChangedEvent(TransitionRoleIdsChangedEvent $event): void
    {
        if ($this->id->isEqual($event->getTransitionId())) {
            $this->roleIds = $event->getRoleIds();
        }
    }
}
