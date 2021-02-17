<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\Importer\Domain\Command\ImporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

class ImportTemplateCommand implements ImporterCommandInterface
{
    private ImportLineId $id;

    private ImportId $importId;

    private string $code;

    public function __construct(ImportLineId $id, ImportId $importId, string $code)
    {
        $this->id = $id;
        $this->importId = $importId;
        $this->code = $code;
    }

    public function getId(): ImportLineId
    {
        return $this->id;
    }

    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
