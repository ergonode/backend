<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\AggregateId;

class ExportLine
{
    /**
     * @var ExportId
     */
    private ExportId $exportId;

    /**
     * @var AggregateId
     */
    private AggregateId $objectId;

    /**
     * @var string|null
     */
    private ?string $error;

    /**
     * @var \DateTime|null
     */
    private ?\DateTime $processedAt;

    /**
     * @param ExportId    $exportId
     * @param AggregateId $objectId
     */
    public function __construct(ExportId $exportId, AggregateId $objectId)
    {
        $this->exportId = $exportId;
        $this->objectId = $objectId;
        $this->processedAt = null;
        $this->error = null;
    }

    /**
     * @return ExportId
     */
    public function getExportId(): ExportId
    {
        return $this->exportId;
    }

    /**
     * @return AggregateId
     */
    public function getObjectId(): AggregateId
    {
        return $this->objectId;
    }

    /**
     * @throws \Exception
     */
    public function process(): void
    {
        $this->processedAt = new \DateTime();
    }

    /**
     * @return bool
     */
    public function isProcessed(): bool
    {
        return null !== $this->processedAt;
    }

    /**
     * @param string $error
     */
    public function addError(string $error): void
    {
        $this->error = $error;
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return null !== $this->error;
    }

    /**
     * @return \DateTime|null
     */
    public function getProcessedAt(): ?\DateTime
    {
        return $this->processedAt;
    }
}
