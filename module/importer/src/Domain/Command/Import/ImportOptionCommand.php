<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class ImportOptionCommand implements DomainCommandInterface
{
    private ImportId $importId;

    private AttributeCode $code;

    private OptionKey $key;

    private TranslatableString $translation;

    public function __construct(
        ImportId $importId,
        AttributeCode $code,
        OptionKey $key,
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

    public function getCode(): AttributeCode
    {
        return $this->code;
    }

    public function getKey(): OptionKey
    {
        return $this->key;
    }

    public function getTranslation(): TranslatableString
    {
        return $this->translation;
    }
}
