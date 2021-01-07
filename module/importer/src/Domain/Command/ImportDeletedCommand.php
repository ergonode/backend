<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command;

class ImportDeletedCommand implements ImporterCommandInterface
{
    /**
     * @JMS\Type("string")
     */
    private string $fileName;

    /**
     * @JMS\Type("string")
     */
    private string $sourceType;

    public function __construct(string $fileName, string $sourceType)
    {
        $this->fileName = $fileName;
        $this->sourceType = $sourceType;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getSourceType(): string
    {
        return $this->sourceType;
    }
}
