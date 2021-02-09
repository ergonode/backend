<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\Command\Import;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Importer\Domain\Command\ImporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;

class ImportCategoryCommand implements ImporterCommandInterface
{
    private ImportId $importId;

    private string $code;

    private TranslatableString $name;

    public function __construct(ImportId $importId, string $code, TranslatableString $name)
    {
        $this->importId = $importId;
        $this->code = $code;
        $this->name = $name;
    }

    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): TranslatableString
    {
        return $this->name;
    }
}
