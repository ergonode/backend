<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\Importer\Domain\Command\ImporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;

class ImportTemplateCommand implements ImporterCommandInterface
{
    private ImportId $importId;

    private string $code;

    public function __construct(ImportId $importId, string $code)
    {
        $this->importId = $importId;
        $this->code = $code;
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
