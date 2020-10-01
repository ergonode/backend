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
     * @param string   $message
     */
    public function __construct(ImportId $importId, string $message)
    {
        $this->importId = $importId;
        $this->message = $message;
        $this->createdAt = new \DateTime();
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
}
