<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Entity;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\EventSourcing\Domain\AbstractEntity;
use Ergonode\Workflow\Domain\Event\Transition\TransitionConditionSetChangedEvent;
use Ergonode\Workflow\Domain\Event\Transition\TransitionRoleIdsChangedEvent;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use JMS\Serializer\Annotation as JMS;

/**
 */
class Transition extends AbstractEntity
{
    /**
     * @var TransitionId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\TransitionId")
     */
    private $id;

    /**
     * @var StatusCode
     *
     * @JMS\Type("Ergonode\Workflow\Domain\ValueObject\StatusCode")
     */
    private $from;

    /**
     * @var StatusCode;
     *
     * @JMS\Type("Ergonode\Workflow\Domain\ValueObject\StatusCode")
     */
    private $to;

    /**
     * @var ConditionSetId|null
     *
     * @JMS\Type("Ergonode\Condition\Domain\Entity\ConditionSetId")
     */
    private $conditionSetId;

    /**
     * @var RoleId[]
     *
     * @JMS\Type("array<string>")
     */
    private $roleIds;

    /**
     * @param TransitionId        $id
     * @param StatusCode          $from
     * @param StatusCode          $to
     * @param array               $roleIds
     * @param ConditionSetId|null $conditionSetId
     */
    public function __construct(TransitionId $id, StatusCode $from, StatusCode $to, array $roleIds = [], ?ConditionSetId $conditionSetId = null)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
        $this->conditionSetId = $conditionSetId;
        $this->roleIds = $roleIds;
    }

    /**
     * @return TransitionId
     */
    public function getId(): TransitionId
    {
        return $this->id;
    }

    /**
     * @return StatusCode
     */
    public function getFrom(): StatusCode
    {
        return $this->from;
    }

    /**
     * @return StatusCode
     */
    public function getTo(): StatusCode
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

    /**
     * @return ConditionSetId|null
     */
    public function getConditionSetId(): ?ConditionSetId
    {
        return $this->conditionSetId;
    }

    /**
     * @param ConditionSetId|null $conditionSetId
     *
     * @throws \Exception
     */
    public function changeConditionSetId(?ConditionSetId $conditionSetId = null): void
    {
        $this->apply(new TransitionConditionSetChangedEvent($conditionSetId));
    }

    /**
     * @param array $roleIds
     *
     * @throws \Exception
     */
    public function changeRoleIds(array $roleIds = []): void
    {
        $this->apply(new TransitionRoleIdsChangedEvent($roleIds));
    }

    /**
     * @param TransitionConditionSetChangedEvent $event
     */
    protected function applyTransitionConditionSetChangedEvent(TransitionConditionSetChangedEvent $event): void
    {
        $this->conditionSetId = $event->getConditionSetId();
    }

    /**
     * @param TransitionRoleIdsChangedEvent $event
     */
    protected function applyTransitionRoleIdsChangedEvent(TransitionRoleIdsChangedEvent $event): void
    {
        $this->roleIds = $event->getRoleIds();
    }
}
