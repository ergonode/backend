<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Domain\AbstractEntity;
use Ergonode\SharedKernel\Domain\Aggregate\TransitionId;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowCreatedEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowDefaultStatusSetEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowStatusAddedEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowStatusRemovedEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionAddedEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionRemovedEvent;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class Workflow extends AbstractAggregateRoot
{
    public const DEFAULT = 'default';

    /**
     * @var WorkflowId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\WorkflowId")
     */
    private WorkflowId $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $code;

    /**
     * @var StatusCode[]
     *
     * @JMS\Type("array<Ergonode\Workflow\Domain\ValueObject\StatusCode>")
     */
    private array $statuses;

    /**
     * @var Transition[]
     *
     * @JMS\Type("array<string, Ergonode\Workflow\Domain\Entity\Transition>")
     */
    private array $transitions;

    /**
     * @var StatusCode|null
     */
    private ?StatusCode $defaultStatus;

    /**
     * @param WorkflowId   $id
     * @param string       $code
     * @param StatusCode[] $statuses
     *
     * @throws \Exception
     */
    public function __construct(WorkflowId $id, string $code, array $statuses = [])
    {
        Assert::allIsInstanceOf($statuses, StatusCode::class);

        $this->apply(new WorkflowCreatedEvent($id, $code, array_values($statuses)));
    }

    /**
     * @return WorkflowId
     */
    public function getId(): WorkflowId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param StatusCode $code
     *
     * @return bool
     */
    public function hasStatus(StatusCode $code): bool
    {
        return isset($this->statuses[$code->getValue()]);
    }

    /**
     * @param StatusCode $code
     *
     * @throws \Exception
     */
    public function setDefaultStatus(StatusCode $code): void
    {
        if (!$this->hasStatus($code)) {
            throw  new \RuntimeException(sprintf('Status "%s" not exists', $code->getValue()));
        }

        if ($this->defaultStatus && !$code->isEqual($this->defaultStatus)) {
            $this->apply(new WorkflowDefaultStatusSetEvent($this->id, $code));
        }
    }

    /**
     * @return bool
     */
    public function hasDefaultStatus(): bool
    {
        return null !== $this->defaultStatus;
    }

    /**
     * @return StatusCode
     */
    public function getDefaultStatus(): StatusCode
    {
        if (!$this->hasDefaultStatus()) {
            throw  new \RuntimeException('Default status not exists');
        }

        return $this->defaultStatus;
    }

    /**
     * @param StatusCode $from
     * @param StatusCode $to
     *
     * @return bool
     */
    public function hasTransition(StatusCode $from, StatusCode $to): bool
    {
        foreach ($this->transitions as $transition) {
            if ($from->isEqual($transition->getFrom()) && $to->isEqual($transition->getTo())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param StatusCode $code
     *
     * @throws \Exception
     */
    public function addStatus(StatusCode $code): void
    {
        if ($this->hasStatus($code)) {
            throw  new \RuntimeException(sprintf('Status "%s" already exists', $code->getValue()));
        }

        $this->apply(new WorkflowStatusAddedEvent($this->id, $code));
    }

    /**
     * @param StatusCode $from
     * @param StatusCode $to
     *
     * @throws \Exception
     */
    public function addTransition(StatusCode $from, StatusCode $to): void
    {
        if ($this->hasTransition($from, $to)) {
            throw  new \RuntimeException(
                sprintf('Transition from "%s" to "%s" already exists', $from->getValue(), $to->getValue())
            );
        }

        if (!$this->hasStatus($from)) {
            throw  new \RuntimeException(sprintf('Transition source status "%s" not exists', $from->getValue()));
        }

        if (!$this->hasStatus($to)) {
            throw  new \RuntimeException(
                sprintf('Transition destination status "%s" not exists', $to->getValue())
            );
        }

        $transition = new Transition(TransitionId::generate(), $from, $to);

        $this->apply(new WorkflowTransitionAddedEvent($this->id, $transition));
    }

    /**
     * @param StatusCode          $from
     * @param StatusCode          $to
     * @param ConditionSetId|null $conditionSetId
     *
     * @throws \Exception
     */
    public function changeTransitionConditionSetId(
        StatusCode $from,
        StatusCode $to,
        ConditionSetId $conditionSetId = null
    ): void {
        if (!$this->hasTransition($from, $to)) {
            throw  new \RuntimeException('Transition not exists');
        }

        if (!$this->hasStatus($from)) {
            throw  new \RuntimeException(sprintf('Transition source status "%s" not exists', $from->getValue()));
        }

        if (!$this->hasStatus($to)) {
            throw  new \RuntimeException(sprintf('Transition destination status "%s" not exists', $to->getValue()));
        }

        $this->getTransition($from, $to)->changeConditionSetId($conditionSetId);
    }

    /**
     * @param StatusCode $from
     * @param StatusCode $to
     * @param array      $roleIds
     *
     * @throws \Exception
     */
    public function changeTransitionRoleIds(StatusCode $from, StatusCode $to, array $roleIds = []): void
    {
        Assert::allIsInstanceOf($roleIds, RoleId::class);

        if (!$this->hasTransition($from, $to)) {
            throw  new \RuntimeException('Transition not exists');
        }

        if (!$this->hasStatus($from)) {
            throw  new \RuntimeException(sprintf('Transition source status "%s" not exists', $from->getValue()));
        }

        if (!$this->hasStatus($to)) {
            throw  new \RuntimeException(sprintf('Transition destination status "%s" not exists', $to->getValue()));
        }

        $this->getTransition($from, $to)->changeRoleIds($roleIds);
    }

    /**
     * @param StatusCode $from
     * @param StatusCode $to
     *
     * @throws \Exception
     */
    public function removeTransition(StatusCode $from, StatusCode $to): void
    {
        $this->apply(new WorkflowTransitionRemovedEvent($this->id, $from, $to));
    }

    /**
     * @param StatusCode $from
     * @param StatusCode $to
     *
     * @return Transition
     */
    public function getTransition(StatusCode $from, StatusCode $to): Transition
    {
        foreach ($this->transitions as $key => $transition) {
            if ($from->isEqual($transition->getFrom()) && $to->isEqual($transition->getTo())) {
                return $transition;
            }
        }

        throw new \RuntimeException(sprintf(
            'Transition from "%s" to "%s" not exists',
            $from->getValue(),
            $to->getValue()
        ));
    }

    /**
     * @return Transition[]
     */
    public function getTransitions(): array
    {
        return $this->transitions;
    }

    /**
     * @param StatusCode $code
     *
     * @return Transition[]
     */
    public function getTransitionsFromStatus(StatusCode $code): array
    {
        $transitions = [];
        foreach ($this->transitions as $transition) {
            if ($code->isEqual($transition->getFrom())) {
                $transitions[] = $transition;
            }
        }

        return $transitions;
    }

    /**
     * @param StatusCode $id
     *
     * @throws \Exception
     */
    public function removeStatus(StatusCode $id): void
    {
        if (!$this->hasStatus($id)) {
            throw  new \RuntimeException(sprintf('Status ID "%s" not exists', $id));
        }

        $this->apply(new WorkflowStatusRemovedEvent($this->id, $id));
    }

    /**
     * @return StatusCode[]
     */
    public function getStatuses(): array
    {
        return array_values($this->statuses);
    }

    /**
     * @param WorkflowCreatedEvent $event
     */
    protected function applyWorkflowCreatedEvent(WorkflowCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->code = $event->getCode();
        $this->statuses = [];
        $this->transitions = [];
        $this->defaultStatus = null;
        foreach ($event->getStatuses() as $status) {
            if (null === $this->defaultStatus) {
                $this->defaultStatus = $status;
            }
            $this->statuses[$status->getValue()] = $status;
        }
    }

    /**
     * @param WorkflowStatusAddedEvent $event
     */
    protected function applyWorkflowStatusAddedEvent(WorkflowStatusAddedEvent $event): void
    {
        $this->statuses[$event->getcode()->getValue()] = $event->getCode();

        if (null === $this->defaultStatus) {
            $this->defaultStatus = $event->getCode();
        }
    }

    /**
     * @param WorkflowStatusRemovedEvent $event
     */
    protected function applyWorkflowStatusRemovedEvent(WorkflowStatusRemovedEvent $event): void
    {
        unset($this->statuses[$event->getCode()->getValue()]);

        if ($this->defaultStatus->isEqual($event->getCode())) {
            $this->defaultStatus = null;
        }

        if (!empty($this->statuses)) {
            $this->defaultStatus = reset($this->statuses);
        }
    }

    /**
     * @param WorkflowTransitionAddedEvent $event
     */
    protected function applyWorkflowTransitionAddedEvent(WorkflowTransitionAddedEvent $event): void
    {
        $this->transitions[$event->getTransition()->getId()->getValue()] = $event->getTransition();
    }

    /**
     * @param WorkflowTransitionRemovedEvent $event
     */
    protected function applyWorkflowTransitionRemovedEvent(WorkflowTransitionRemovedEvent $event): void
    {
        foreach ($this->transitions as $key => $transition) {
            if ($event->getSource()->isEqual($transition->getFrom()) &&
                $event->getDestination()->isEqual($transition->getTo())
            ) {
                unset($this->transitions[$key]);
            }
        }
    }

    /**
     * @param WorkflowDefaultStatusSetEvent $event
     */
    protected function applyWorkflowDefaultStatusSetEvent(WorkflowDefaultStatusSetEvent $event): void
    {
        $this->defaultStatus = $event->getCode();
    }

    /**
     * @return AbstractEntity[]
     */
    protected function getEntities(): array
    {
        return $this->transitions;
    }
}
