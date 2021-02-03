<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\Importer\Domain\Command\ImporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class ImportOptionCommand implements ImporterCommandInterface
{
    private ImportId $importId;

    private string $code;

    private string $key;

    private TranslatableString $translation;

    public function __construct(
        ImportId $importId,
        string $code,
        string $key,
        TranslatableString $translation
    ) {
        $this->importId = $importId;
        $this->code = $code;
        $this->key = $key;
        $this->translation = $translation;
    }

    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getTranslation(): TranslatableString
    {
        return $this->translation;
    }
}
