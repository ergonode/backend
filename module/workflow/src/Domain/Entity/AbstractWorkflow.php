<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\TransitionId;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowCreatedEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowDefaultStatusSetEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowStatusAddedEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowStatusRemovedEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionAddedEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionRemovedEvent;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Event\Transition\TransitionRoleIdsChangedEvent;
use Ergonode\Workflow\Domain\Condition\WorkflowConditionInterface;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionConditionsChangedEvent;
use Ergonode\Workflow\Domain\Event\Transition\TransitionConditionSetChangedEvent;

abstract class AbstractWorkflow extends AbstractAggregateRoot implements WorkflowInterface
{
    public const DEFAULT = 'default';

    protected WorkflowId $id;

    protected string $code;

    /**
     * @var StatusId[]
     */
    protected array $statuses;

    /**
     * @var Transition[]
     */
    protected array $transitions;

    protected ?StatusId $defaultId;

    /**
     * @param StatusId[] $statuses
     *
     * @throws \Exception
     */
    public function __construct(WorkflowId $id, string $code, array $statuses = [])
    {
        Assert::allIsInstanceOf($statuses, StatusId::class);

        $this->apply(new WorkflowCreatedEvent($id, get_class($this), $code, array_values($statuses)));
    }

    public function getId(): WorkflowId
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    abstract public static function getType(): string;


    public function hasStatus(StatusId $id): bool
    {
        return isset($this->statuses[$id->getValue()]);
    }

    /**
     * @throws \Exception
     */
    public function setDefaultStatus(StatusId $id): void
    {
        if (!$this->hasStatus($id)) {
            throw  new \RuntimeException(sprintf('Status "%s" not exists', $id->getValue()));
        }

        if ($this->defaultId && !$id->isEqual($this->defaultId)) {
            $this->apply(new WorkflowDefaultStatusSetEvent($this->id, $id));
        }
    }

    public function hasDefaultStatus(): bool
    {
        return null !== $this->defaultId;
    }

    public function getDefaultStatus(): StatusId
    {
        if (!$this->hasDefaultStatus()) {
            throw  new \RuntimeException('Default status not exists');
        }

        return $this->defaultId;
    }

    public function hasTransition(StatusId $from, StatusId $to): bool
    {
        foreach ($this->transitions as $transition) {
            if ($from->isEqual($transition->getFrom()) && $to->isEqual($transition->getTo())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws \Exception
     */
    public function addStatus(StatusId $id): void
    {
        if ($this->hasStatus($id)) {
            throw  new \RuntimeException(sprintf('Status "%s" already exists', $id->getValue()));
        }

        $this->apply(new WorkflowStatusAddedEvent($this->id, $id));
    }

    /**
     * @throws \Exception
     */
    public function addTransition(StatusId $from, StatusId $to): void
    {
        if ($this->hasTransition($from, $to)) {
            throw  new \RuntimeException(
                sprintf('Transition from "%s" to "%s" already exists', $from->getValue(), $to->getValue())
            );
        }

        if (!$this->hasStatus($from)) {
            throw  new \RuntimeException(sprintf('Transition from status "%s" not exists', $from->getValue()));
        }

        if (!$this->hasStatus($to)) {
            throw  new \RuntimeException(
                sprintf('Transition to status "%s" not exists', $to->getValue())
            );
        }

        $transition = new Transition(TransitionId::generate(), $from, $to);

        $this->apply(new WorkflowTransitionAddedEvent($this->id, $transition));
    }

    /**
     * @param WorkflowConditionInterface[] $conditions
     */
    public function changeTransitionConditions(StatusId $from, StatusId $to, array $conditions): void
    {
        Assert::allIsInstanceOf($conditions, WorkflowConditionInterface::class);

        if (!$this->hasTransition($from, $to)) {
            throw  new \RuntimeException(
                sprintf('Transition from "%s" to "%s" not exists', $from->getValue(), $to->getValue())
            );
        }

        $this->apply(new WorkflowTransitionConditionsChangedEvent($this->id, $from, $to, $conditions));
    }

    /**
     * @param array $roleIds
     *
     * @throws \Exception
     */
    public function changeTransitionRoleIds(StatusId $from, StatusId $to, array $roleIds = []): void
    {
        Assert::allIsInstanceOf($roleIds, RoleId::class);

        if (!$this->hasTransition($from, $to)) {
            throw  new \RuntimeException('Transition not exists');
        }

        $transitionId = $this->getTransition($from, $to)->getId();

        $this->apply(new TransitionRoleIdsChangedEvent($this->getId(), $transitionId, $roleIds));
    }

    /**
     * @throws \Exception
     */
    public function removeTransition(StatusId $from, StatusId $to): void
    {
        $this->apply(new WorkflowTransitionRemovedEvent($this->id, $from, $to));
    }

    public function getTransition(StatusId $from, StatusId $to): Transition
    {
        foreach ($this->transitions as $transition) {
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
     *
     * @return RoleId[]
     */
    public function getTransitionRoleIds(StatusId $from, StatusId $to): array
    {
        foreach ($this->transitions as $transition) {
            if ($from->isEqual($transition->getFrom()) && $to->isEqual($transition->getTo())) {
                return $transition->getRoleIds();
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

    /*
     * @return Transition[]
     */
    public function getTransitionsFromStatus(StatusId $id): array
    {
        $transitions = [];
        foreach ($this->transitions as $transition) {
            if ($id->isEqual($transition->getFrom())) {
                $transitions[] = $transition;
            }
        }

        return $transitions;
    }

    /**
     * @throws \Exception
     */
    public function removeStatus(StatusId $id): void
    {
        if (!$this->hasStatus($id)) {
            throw  new \RuntimeException(sprintf('Status ID "%s" not exists', $id));
        }

        $this->apply(new WorkflowStatusRemovedEvent($this->id, $id));
    }

    /**
     * @return StatusId[]
     */
    public function getStatuses(): array
    {
        return array_values($this->statuses);
    }

    /**
     * @return StatusId[]
     *
     * @deprecated
     */
    public function getSortedTransitionStatuses(): array
    {
        @trigger_error(
            'Ergonode\Workflow\Domain\Entity\getSortedTransitionStatuses is deprecated and will be removed in 2.0',
            \E_USER_DEPRECATED,
        );

        $transitions = $this->transitions;
        $code = $this->getDefaultStatus();
        $sorted = [$code];
        $transitions = new \ArrayIterator($transitions);
        for (; $transitions->valid(); $hit ? $transitions->rewind() : $transitions->next()) {
            $transition = $transitions->current();
            $hit = false;

            if (!$code->isEqual($transition->getFrom())) {
                continue;
            }
            // avoids infinite loop
            if ($this->getDefaultStatus()->isEqual($transition->getTo())) {
                break;
            }
            $code = $sorted[] = $transition->getTo();

            $transitions->offsetUnset($transitions->key());
            $hit = true;
        }

        return $sorted;
    }


    protected function applyWorkflowCreatedEvent(WorkflowCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->code = $event->getCode();
        $this->statuses = [];
        $this->transitions = [];
        $this->defaultId = null;
        foreach ($event->getStatuses() as $status) {
            if (null === $this->defaultId) {
                $this->defaultId = $status;
            }
            $this->statuses[$status->getValue()] = $status;
        }
    }

    protected function applyWorkflowStatusAddedEvent(WorkflowStatusAddedEvent $event): void
    {
        $this->statuses[$event->getStatusId()->getValue()] = $event->getStatusId();

        if (null === $this->defaultId) {
            $this->defaultId = $event->getStatusId();
        }
    }

    protected function applyWorkflowStatusRemovedEvent(WorkflowStatusRemovedEvent $event): void
    {
        unset($this->statuses[$event->getStatusId()->getValue()]);

        if ($this->defaultId->isEqual($event->getStatusId())) {
            $this->defaultId = null;
        }

        if (!empty($this->statuses)) {
            $this->defaultId = reset($this->statuses);
        }
    }

    protected function applyWorkflowTransitionAddedEvent(WorkflowTransitionAddedEvent $event): void
    {
        $this->transitions[$event->getTransition()->getId()->getValue()] = $event->getTransition();
    }

    protected function applyWorkflowTransitionRemovedEvent(WorkflowTransitionRemovedEvent $event): void
    {
        foreach ($this->transitions as $key => $transition) {
            if ($event->getFrom()->isEqual($transition->getFrom()) &&
                $event->getTo()->isEqual($transition->getTo())
            ) {
                unset($this->transitions[$key]);
            }
        }
    }

    protected function applyWorkflowDefaultStatusSetEvent(WorkflowDefaultStatusSetEvent $event): void
    {
        $this->defaultId = $event->getStatusId();
    }

    protected function applyTransitionRoleIdsChangedEvent(TransitionRoleIdsChangedEvent $event): void
    {
        $id = $event->getTransitionId();

        $transition = $this->transitions[$id->getValue()];

        $this->transitions[$id->getValue()] = new Transition(
            $transition->getId(),
            $transition->getFrom(),
            $transition->getTo(),
            $event->getRoleIds(),
            $transition->getConditions(),
        );
    }

    /**
     * @deprecated
     */
    protected function applyTransitionConditionSetChangedEvent(TransitionConditionSetChangedEvent $event): void
    {
        $id = $event->getTransitionId();

        $transition = $this->transitions[$id->getValue()];

        $this->transitions[$id->getValue()] = new Transition(
            $transition->getId(),
            $transition->getFrom(),
            $transition->getTo(),
            $transition->getRoleIds(),
            $transition->getConditions(),
        );
    }

    protected function applyWorkflowTransitionConditionsChangedEvent(
        WorkflowTransitionConditionsChangedEvent $event
    ): void {
        $transition = $this->getTransition($event->getFrom(), $event->getTo());

        $this->transitions[$transition->getId()->getValue()] = new Transition(
            $transition->getId(),
            $transition->getFrom(),
            $transition->getTo(),
            $transition->getRoleIds(),
            $event->getConditions(),
        );
    }
}
