<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Entity;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Importer\Domain\Entity\ImportId;
use Ergonode\Transformer\Domain\Event\ProcessorCreatedEvent;
use Ergonode\Transformer\Domain\Event\ProcessorStatusChangedEvent;
use Ergonode\Transformer\Domain\ValueObject\ProcessorStatus;

/**
 */
class Processor extends AbstractAggregateRoot
{
    /**
     * @var ProcessorId
     */
    private $id;

    /**
     * @var TransformerId
     */
    private $transformerId;

    /**
     * @var ImportId
     */
    private $importId;

    /**
     * @var string
     */
    private $action;

    /**
     * @var ProcessorStatus
     */
    private $status;

    /**
     * @param ProcessorId   $id
     * @param TransformerId $transformerId
     * @param ImportId      $importId
     * @param string        $action
     */
    public function __construct(ProcessorId $id, TransformerId $transformerId, ImportId $importId, string $action)
    {
        $this->apply(new ProcessorCreatedEvent($id, $importId, $transformerId, $action));
    }

    /**
     * @return ProcessorId
     */
    public function getId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return TransformerId
     */
    public function getTransformerId(): TransformerId
    {
        return $this->transformerId;
    }

    /**
     * @return ImportId
     */
    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return ProcessorStatus
     */
    public function getStatus(): ProcessorStatus
    {
        return $this->status;
    }

    /**
     */
    public function process(): void
    {
        if (!$this->getStatus()->isCreated()) {
            throw new \LogicException(
                \sprintf('Can\'t change status to %s from %s', ProcessorStatus::PRECESSED, $this->getStatus())
            );
        }

        $this->apply(
            new ProcessorStatusChangedEvent($this->id, $this->status, new ProcessorStatus(ProcessorStatus::PRECESSED))
        );
    }

    /**
     * @param string|null $reason
     */
    public function stop(string $reason = null): void
    {
        if ($this->getStatus()->isStopped()) {
            throw new \LogicException(
                \sprintf('Can\'t change status to %s from %s', ProcessorStatus::STOPPED, $this->getStatus())
            );
        }

        $this->apply(
            new ProcessorStatusChangedEvent(
                $this->id,
                $this->status,
                new ProcessorStatus(ProcessorStatus::STOPPED),
                $reason
            )
        );
    }

    /**
     */
    public function end(): void
    {
        if (!$this->getStatus()->isProcessed()) {
            throw new \LogicException(
                \sprintf('Can\'t change status to %s from %s', ProcessorStatus::ENDED, $this->getStatus())
            );
        }

        $this
            ->apply(
                new ProcessorStatusChangedEvent($this->id, $this->status, new ProcessorStatus(ProcessorStatus::ENDED))
            );
    }

    /**
     * @param ProcessorCreatedEvent $event
     */
    protected function applyProcessorCreatedEvent(ProcessorCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->transformerId = $event->getTransformerId();
        $this->importId = $event->getImportId();
        $this->action = $event->getAction();
        $this->status = new ProcessorStatus();
    }

    /**
     * @param ProcessorStatusChangedEvent $event
     */
    protected function applyProcessorStatusChangedEvent(ProcessorStatusChangedEvent $event): void
    {
        $this->status = $event->getTo();
    }
}
