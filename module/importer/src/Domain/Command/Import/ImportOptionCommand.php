<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\Importer\Domain\Command\ImporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

class ImportOptionCommand implements ImporterCommandInterface
{
    private ImportLineId $id;

    private ImportId $importId;

    private string $code;

    private string $optionKey;

    private TranslatableString $translation;

    public function __construct(
        ImportLineId $id,
        ImportId $importId,
        string $code,
        string $key,
        TranslatableString $translation
    ) {
        $this->id = $id;
        $this->importId = $importId;
        $this->code = $code;
        $this->optionKey = $key;
        $this->translation = $translation;
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

    public function getOptionKey(): string
    {
        return $this->optionKey;
    }

    public function getTranslation(): TranslatableString
    {
        return $this->translation;
    }
}
