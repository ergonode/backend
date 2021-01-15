<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\AggregateId;

class ExportLine
{
    private ExportId $exportId;

    private AggregateId $objectId;

    private ?string $error;

    private array $parameters;

    private ?\DateTime $processedAt;

    public function __construct(ExportId $exportId, AggregateId $objectId)
    {
        $this->exportId = $exportId;
        $this->objectId = $objectId;
        $this->processedAt = null;
        $this->error = null;
        $this->parameters = [];
    }

    public function getExportId(): ExportId
    {
        return $this->exportId;
    }

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

    public function isProcessed(): bool
    {
        return null !== $this->processedAt;
    }

    public function addError(string $error, array $parameters = []): void
    {
        $this->error = $error;
        $this->parameters = $parameters;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function hasError(): bool
    {
        return null !== $this->error;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getProcessedAt(): ?\DateTime
    {
        return $this->processedAt;
    }
}
