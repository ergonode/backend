<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\Importer\Domain\Command\ImporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;

class UploadFileCommand implements ImporterCommandInterface
{
    private ImportId $id;
    private SourceId $sourceId;

    private string $fileName;

    public function __construct(ImportId $id, SourceId $sourceId, string $fileName)
    {
        $this->id = $id;
        $this->sourceId = $sourceId;
        $this->fileName = $fileName;
    }

    public function getId(): ImportId
    {
        return $this->id;
    }

    public function getSourceId(): SourceId
    {
        return $this->sourceId;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }
}
