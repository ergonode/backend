<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\ImportId;

/**
 */
class ImportLine
{
    /**
     * @var int
     */
    private int $step;

    /**
     * @var int
     */
    private int $line;

    /**
     * @var ImportId
     */
    private ImportId $importId;

    /**
     * @var string|null
     */
    private ?string $error;

    /**
     * @var \DateTime|null
     */
    private ?\DateTime $processedAt;

    /**
     * @param ImportId $importId
     * @param int      $step
     * @param int      $line
     */
    public function __construct(ImportId $importId, int $step, int $line)
    {
        $this->importId = $importId;
        $this->step = $step;
        $this->line = $line;
        $this->error = null;
        $this->processedAt = null;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * @return ImportId
     */
    public function getImportId(): ImportId
    {
        return $this->importId;
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
     * @return int
     */
    public function getStep(): int
    {
        return $this->step;
    }

    /**
     * @return \DateTime|null
     */
    public function getProcessedAt(): ?\DateTime
    {
        return $this->processedAt;
    }
}
