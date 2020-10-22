<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Importer\Infrastructure\Exception\ImportException;

class ImportError
{
    private ImportId $importId;

    private string $message;

    private \DateTime $createdAt;

    public function __construct(ImportId $importId, string $message)
    {
        $this->importId = $importId;
        $this->message = $message;
        $this->createdAt = new \DateTime();
    }

    /**
     * @return static
     */
    public static function createFromImportException(ImportId $importId, ImportException $exception): self
    {
        return new self($importId, $exception->getMessage());
    }

    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
