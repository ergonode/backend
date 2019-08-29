<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowCreatedEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowStatusAddedEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowStatusRemovedEvent;
use Ergonode\Workflow\Domain\ValueObject\Status;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionAddedEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionRemovedEvent;
use Ergonode\Workflow\Domain\ValueObject\Transition;
use Webmozart\Assert\Assert;
use JMS\Serializer\Annotation as JMS;

/**
 */
class Workflow extends AbstractAggregateRoot
{
    public const DEFAULT = 'default';

    /**
     * @var WorkflowId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\WorkflowId")
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $code;

    /**
     * @var StatusId[]
     *
     * @JMS\Type("array<Ergonode\Workflow\Domain\Entity\StatusId>")
     */
    private $statuses;

    /**
     * @var ArrayCollection|Transition[]
     *
     * @JMS\Type("ArrayCollection<Ergonode\Workflow\Domain\ValueObject\Transition>")
     */
    private $transitions;

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

        $this->apply(new WorkflowCreatedEvent($id, $code, array_values($statuses)));
    }

    /**
     * @return WorkflowId
     */
    public function getId(): AbstractId
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
     * @param StatusId $id
     *
     * @return bool
     */
    public function hasStatus(StatusId $id): bool
    {
        return isset($this->statuses[$id->getValue()]);
    }

    /**
     * @param StatusId $source
     * @param StatusId $destination
     *
     * @return bool
     */
    public function hasTransition(StatusId $source, StatusId $destination): bool
    {
        $key = $this->getTransitionKey($source, $destination);

        return isset($this->transitions[$key]);
    }

    /**
     * @param StatusId $id
     *
     * @throws \Exception
     */
    public function addStatus(StatusId $id): void
    {
        if ($this->hasStatus($id)) {
            throw  new \RuntimeException(sprintf('Status %s already exists', $code));
        }

        $this->apply(new WorkflowStatusAddedEvent($id));
    }

    /**
     * @param Transition $transition
     *
     * @throws \Exception
     */
    public function addTransition(Transition $transition): void
    {
        if ($this->hasTransition($transition->getSource(), $transition->getDestination())) {
            throw  new \RuntimeException(sprintf('Transition %s already exists', $transition->getName()));
        }

        if (!$this->hasStatus($transition->getSource())) {
            throw  new \RuntimeException(sprintf('Transition source status %s not exists', $transition->getSource()->getValue()));
        }

        if (!$this->hasStatus($transition->getDestination())) {
            throw  new \RuntimeException(sprintf('Transition destination status %s not exists', $transition->getDestination()->getValue()));
        }

        $this->apply(new WorkflowTransitionAddedEvent($transition));
    }

    /**
     * @param StatusId $source
     * @param StatusId $destination
     *
     * @throws \Exception
     */
    public function removeTransition(StatusId $source, StatusId $destination): void
    {
        $this->apply(new WorkflowTransitionRemovedEvent($source, $destination));
    }

    /**
     * @return Transition[]
     */
    public function getTransitions(): array
    {
        return $this->transitions->toArray();
    }

    /**
     * @param StatusId $id
     *
     * @throws \Exception
     */
    public function removeStatus(StatusId $id): void
    {
        if (!$this->hasStatus($id)) {
            throw  new \RuntimeException(sprintf('Status id %s not exists', $id));
        }

        $this->apply(new WorkflowStatusRemovedEvent($id));
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
        $this->id = $event->getId();
        $this->code = $event->getCode();
        $this->statuses = [];
        $this->transitions = new ArrayCollection();
        foreach ($event->getStatuses() as $status) {
            $this->statuses[$status->getValue()] = $status;
        }
    }

    /**
     * @param WorkflowStatusAddedEvent $event
     */
    protected function applyWorkflowStatusAddedEvent(WorkflowStatusAddedEvent $event): void
    {
        $this->statuses[$event->getId()->getValue()] = $event->getId();
    }

    /**
     * @param WorkflowStatusRemovedEvent $event
     */
    protected function applyWorkflowStatusRemovedEvent(WorkflowStatusRemovedEvent $event): void
    {
        unset($this->statuses[$event->getId()->getValue()]);
    }

    /**
     * @param WorkflowTransitionAddedEvent $event
     */
    protected function applyWorkflowTransitionAddedEvent(WorkflowTransitionAddedEvent $event): void
    {
        $key = $this->getTransitionKey($event->getTransition()->getSource(), $event->getTransition()->getDestination());

        $this->transitions[$key] = $event->getTransition();
    }

    /**
     * @param WorkflowTransitionRemovedEvent $event
     */
    protected function applyWorkflowTransitionRemovedEvent(WorkflowTransitionRemovedEvent $event): void
    {
        $key = $this->getTransitionKey($event->getSource(), $event->getDestination());

        unset($this->transitions[$key]);
    }

    /**
     * @param StatusId $source
     * @param StatusId $destination
     *
     * @return string
     */
    private function getTransitionKey(StatusId $source, StatusId $destination): string
    {
        return sprintf('%s:%s', $source->getValue(), $destination->getValue());
    }
}
