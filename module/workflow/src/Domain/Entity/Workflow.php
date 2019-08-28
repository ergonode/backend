<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Entity;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowCreatedEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowStatusAddedEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowStatusChangedEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowStatusRemovedEvent;
use Ergonode\Workflow\Domain\ValueObject\Status;
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
     * @var Status[]
     *
     * @JMS\Type("array<string, Ergonode\Workflow\Domain\ValueObject\Status>")
     */
    private $statuses;

    /**
     * @param WorkflowId $id
     * @param string     $code
     * @param array      $statuses
     *
     * @throws \Exception
     */
    public function __construct(WorkflowId $id, string $code, array $statuses = [])
    {
        Assert::allIsInstanceOf($statuses, Status::class);

        $this->apply(new WorkflowCreatedEvent($id, $code, $statuses));
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
     * @param string $code
     *
     * @return bool
     */
    public function hasStatus(string $code): bool
    {
        return isset($this->statuses[$code]);
    }

    /**
     * @param string $code
     *
     * @return Status
     */
    public function getStatus(string $code): Status
    {
        if (!$this->hasStatus($code)) {
            throw  new \RuntimeException(sprintf('Status %s not exists', $code));
        }

        return $this->statuses[$code];
    }

    /**
     * @param string $code
     * @param Status $status
     *
     * @throws \Exception
     */
    public function addStatus(string $code, Status $status): void
    {
        if ($this->hasStatus($code)) {
            throw  new \RuntimeException(sprintf('Status %s already exists', $code));
        }

        $this->apply(new WorkflowStatusAddedEvent($code, $status));
    }

    /**
     * @param string $code
     * @param Status $status
     *
     * @throws \Exception
     */
    public function changeStatus(string $code, Status $status): void
    {
        if (!$this->hasStatus($code)) {
            throw  new \RuntimeException(sprintf('Status %s not exists', $code));
        }

        $this->apply(new WorkflowStatusChangedEvent($code, $this->statuses[$code], $status));
    }

    /**
     * @param string $code
     *
     * @throws \Exception
     */
    public function removeStatus(string $code): void
    {
        if (!$this->hasStatus($code)) {
            throw  new \RuntimeException(sprintf('Status %s not exists', $code));
        }

        $this->apply(new WorkflowStatusRemovedEvent($code));
    }

    /**
     * @return Status[]
     */
    public function getStatuses(): array
    {
        return $this->statuses;
    }

    /**
     * @param WorkflowCreatedEvent $event
     */
    protected function applyWorkflowCreatedEvent(WorkflowCreatedEvent $event): void
    {
        $this->id = $event->getId();
        $this->code = $event->getCode();
        $this->statuses = $event->getStatuses();
    }

    /**
     * @param WorkflowStatusAddedEvent $event
     */
    protected function applyWorkflowStatusAddedEvent(WorkflowStatusAddedEvent $event): void
    {
        $this->statuses[$event->getCode()] = $event->getStatus();
    }

    /**
     * @param WorkflowStatusChangedEvent $event
     */
    protected function applyWorkflowStatusChangedEvent(WorkflowStatusChangedEvent $event): void
    {
        $this->statuses[$event->getCode()] = $event->getTo();
    }

    /**
     * @param WorkflowStatusRemovedEvent $event
     */
    protected function applyWorkflowStatusRemovedEvent(WorkflowStatusRemovedEvent $event): void
    {
        unset($this->statuses[$event->getCode()]);
    }
}
