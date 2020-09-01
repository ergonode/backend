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
class ImportError
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
     * @var string
     */
    private string $message;

    /**
     * @var \DateTime
     */
    private \DateTime $createdAt;

    /**
     * @param ImportId $importId
     * @param int      $step
     * @param int      $line
     * @param string   $message
     */
    public function __construct(ImportId $importId, int $step, int $line, string $message)
    {
        $this->importId = $importId;
        $this->step = $step;
        $this->line = $line;
        $this->message = $message;
        $this->createdAt = new \DateTime();
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
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getStep(): int
    {
        return $this->step;
    }
}
