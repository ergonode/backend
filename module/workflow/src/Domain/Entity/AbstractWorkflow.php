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
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

/**
 */
abstract class AbstractWorkflow extends AbstractAggregateRoot implements WorkflowInterface
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
     * @var StatusId[]
     *
     * @JMS\Type("array<Ergonode\SharedKernel\Domain\Aggregate\StatusId>")
     */
    private array $statuses;

    /**
     * @var Transition[]
     *
     * @JMS\Type("array<string, Ergonode\Workflow\Domain\Entity\Transition>")
     */
    private array $transitions;

    /**
     * @var StatusId|null
     */
    private ?StatusId $defaultId;

    /**
     * @param WorkflowId $id
     * @param string     $code
     * @param StatusId[] $statuses
     *
     * @throws \Exception
     */
    public function __construct(WorkflowId $id, string $code, array $statuses = [])
    {
        Assert::allIsInstanceOf($statuses, StatusId::class);

        $this->apply(new WorkflowCreatedEvent($id, get_class($this), $code, array_values($statuses)));
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
     * @return string
     */
    abstract public function getType(): string;


    /**
     * @param StatusId $id
     *
     * @return bool
     */
    public function hasStatus(StatusId $id): bool
    {
        return isset($this->statuses[$id->getValue()]);
    }

    /**
     * @param StatusId $id
     *
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

    /**
     * @return bool
     */
    public function hasDefaultStatus(): bool
    {
        return null !== $this->defaultId;
    }

    /**
     * @return StatusId
     */
    public function getDefaultStatus(): StatusId
    {
        if (!$this->hasDefaultStatus()) {
            throw  new \RuntimeException('Default status not exists');
        }

        return $this->defaultId;
    }

    /**
     * @param StatusId $from
     * @param StatusId $to
     *
     * @return bool
     */
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
     * @param StatusId $id
     *
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
     * @param StatusId $from
     * @param StatusId $to
     *
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
     * @param StatusId            $from
     * @param StatusId            $to
     * @param ConditionSetId|null $conditionSetId
     *
     * @throws \Exception
     */
    public function changeTransitionConditionSetId(
        StatusId $from,
        StatusId $to,
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
     * @param StatusId $from
     * @param StatusId $to
     * @param array    $roleIds
     *
     * @throws \Exception
     */
    public function changeTransitionRoleIds(StatusId $from, StatusId $to, array $roleIds = []): void
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
     * @param StatusId $from
     * @param StatusId $to
     *
     * @throws \Exception
     */
    public function removeTransition(StatusId $from, StatusId $to): void
    {
        $this->apply(new WorkflowTransitionRemovedEvent($this->id, $from, $to));
    }

    /**
     * @param StatusId $from
     * @param StatusId $to
     *
     * @return Transition
     */
    public function getTransition(StatusId $from, StatusId $to): Transition
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
     * @param StatusId $id
     *
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
     * @param StatusId $id
     *
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
     * @param WorkflowCreatedEvent $event
     */
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

    /**
     * @param WorkflowStatusAddedEvent $event
     */
    protected function applyWorkflowStatusAddedEvent(WorkflowStatusAddedEvent $event): void
    {
        $this->statuses[$event->getStatusId()->getValue()] = $event->getStatusId();

        if (null === $this->defaultId) {
            $this->defaultId = $event->getStatusId();
        }
    }

    /**
     * @param WorkflowStatusRemovedEvent $event
     */
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
        $this->defaultId = $event->getStatusId();
    }

    /**
     * @return AbstractEntity[]
     */
    protected function getEntities(): array
    {
        return $this->transitions;
    }
}
