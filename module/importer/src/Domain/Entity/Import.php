<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Entity;

use Ergonode\Importer\Domain\Entity\Source\SourceId;
use Ergonode\Importer\Domain\ValueObject\ImportStatus;
use Ergonode\Transformer\Domain\Entity\TransformerId;

/**
 */
class Import
{
    /**
     * @var ImportId;
     */
    protected ImportId $id;

    /**
     * @var SourceId
     */
    protected SourceId $sourceId;

    /**
     * @var TransformerId
     */
    protected TransformerId $transformerId;

    /**
     * @var ImportStatus
     */
    protected ImportStatus $status;

    /**
     * @var \DateTime|null
     */
    private ?\DateTime $startedAt;

    /**
     * @var \DateTime|null
     */
    private ?\DateTime $endedAt;

    /**
     * @param ImportId      $id
     * @param SourceId      $sourceId
     * @param TransformerId $transformerId
     */
    public function __construct(ImportId $id, SourceId $sourceId, TransformerId $transformerId)
    {
        $this->id = $id;
        $this->sourceId = $sourceId;
        $this->transformerId = $transformerId;
        $this->status = new ImportStatus(ImportStatus::CREATED);
        $this->startedAt = null;
        $this->endedAt = null;
    }

    /**
     * @return ImportId
     */
    public function getId(): ImportId
    {
        return $this->id;
    }

    /**
     * @return SourceId
     */
    public function getSourceId(): SourceId
    {
        return $this->sourceId;
    }

    /**
     * @return TransformerId
     */
    public function getTransformerId(): TransformerId
    {
        return $this->transformerId;
    }

    /**
     * @return ImportStatus
     */
    public function getStatus(): ImportStatus
    {
        return $this->status;
    }

    /**
     * @return \DateTime|null
     */
    public function getStartedAt(): ?\DateTime
    {
        return $this->startedAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getEndedAt(): ?\DateTime
    {
        return $this->endedAt;
    }

    /**
     */
    public function start(): void
    {
        if (!$this->getStatus()->isCreated()) {
            throw new \LogicException(
                \sprintf('Can\'t change status to %s from %s', ImportStatus::PRECESSED, $this->getStatus())
            );
        }

        $this->status = new ImportStatus(ImportStatus::PRECESSED);
        $this->startedAt = new \DateTime();
    }

    /**
     */
    public function stop(): void
    {
        if ($this->getStatus()->isStopped()) {
            throw new \LogicException(
                \sprintf('Can\'t change status to %s from %s', ImportStatus::STOPPED, $this->getStatus())
            );
        }

        $this->status = new ImportStatus(ImportStatus::STOPPED);
    }

    /**
     */
    public function end(): void
    {
        if (!$this->getStatus()->isProcessed()) {
            throw new \LogicException(
                \sprintf('Can\'t change status to %s from %s', ImportStatus::ENDED, $this->getStatus())
            );
        }

        $this->status = new ImportStatus(ImportStatus::ENDED);
        $this->endedAt = new \DateTime();
    }
}
