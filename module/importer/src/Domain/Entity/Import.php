<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Ergonode\Importer\Domain\ValueObject\ImportStatus;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;

class Import
{
    public const SUCCESS_LINE_STATUS = 'success';
    public const FAILURE_LINE_STATUS = 'failure';

    protected ImportId $id;

    protected SourceId $sourceId;

    protected ImportStatus $status;

    private ?\DateTime $startedAt;

    private ?\DateTime $endedAt;

    private string $file;

    public function __construct(ImportId $id, SourceId $sourceId, string $file)
    {
        $this->id = $id;
        $this->sourceId = $sourceId;
        $this->status = new ImportStatus(ImportStatus::CREATED);
        $this->file = $file;
        $this->startedAt = null;
        $this->endedAt = null;
    }

    public function getId(): ImportId
    {
        return $this->id;
    }

    public function getSourceId(): SourceId
    {
        return $this->sourceId;
    }

    public function getStatus(): ImportStatus
    {
        return $this->status;
    }

    public function getStartedAt(): ?\DateTime
    {
        return $this->startedAt;
    }

    public function getEndedAt(): ?\DateTime
    {
        return $this->endedAt;
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function getFileHash(): string
    {
        return sha1($this->file);
    }

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

    public function stop(): void
    {
        if ($this->getStatus()->isStopped()) {
            throw new \LogicException(
                \sprintf('Can\'t change status to %s from %s', ImportStatus::STOPPED, $this->getStatus())
            );
        }

        $this->status = new ImportStatus(ImportStatus::STOPPED);
    }

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
