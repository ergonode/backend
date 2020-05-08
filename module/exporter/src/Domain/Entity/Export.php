<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Entity;

use Ergonode\Exporter\Domain\ValueObject\ExportStatus;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

/**
 */
class Export
{
    /**
     * @var ExportId
     */
    private ExportId $id;

    /**
     * @var ChannelId
     */
    private ChannelId $channelId;

    /**
     * @var ExportStatus
     */
    private ExportStatus $status;

    /**
     * @var \DateTime|null
     */
    private ?\DateTime $startedAt;

    /**
     * @var \DateTime|null
     */
    private ?\DateTime $endedAt;

    /**
     * @param ExportId  $exportId
     * @param ChannelId $channelId
     */
    public function __construct(ExportId $exportId, ChannelId $channelId)
    {
        $this->id = $exportId;
        $this->channelId = $channelId;
        $this->status = new ExportStatus(ExportStatus::CREATED);
        $this->startedAt = null;
        $this->endedAt = null;
    }

    /**
     * @return ExportId
     */
    public function getId(): ExportId
    {
        return $this->id;
    }

    /**
     * @return ChannelId
     */
    public function getChannelId(): ChannelId
    {
        return $this->channelId;
    }

    /**
     * @return ExportStatus
     */
    public function getStatus(): ExportStatus
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
                \sprintf('Can\'t change status to %s from %s', ExportStatus::PRECESSED, $this->getStatus())
            );
        }

        $this->status = new ExportStatus(ExportStatus::PRECESSED);
        $this->startedAt = new \DateTime();
    }

    /**
     */
    public function stop(): void
    {
        if ($this->getStatus()->isStopped()) {
            throw new \LogicException(
                \sprintf('Can\'t change status to %s from %s', ExportStatus::STOPPED, $this->getStatus())
            );
        }

        $this->status = new ExportStatus(ExportStatus::STOPPED);
    }
    /**
     */
    public function end(): void
    {
        if (!$this->getStatus()->isProcessed()) {
            throw new \LogicException(
                \sprintf('Can\'t change status to %s from %s', ExportStatus::ENDED, $this->getStatus())
            );
        }

        $this->status = new ExportStatus(ExportStatus::ENDED);
        $this->endedAt = new \DateTime();
    }
}
