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
    /**
     * @var ImportId
     */
    private ImportId $importId;

    /**
     * @var AttributeCode
     */
    private AttributeCode $code;

    /**
     * @var OptionKey $key
     */
    private OptionKey $key;

    /**
     * @var TranslatableString
     */
    private TranslatableString $translation;

    /**
     * @param ImportId           $importId
     * @param AttributeCode      $code
     * @param OptionKey          $key
     * @param TranslatableString $translation
     */
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

    /**
     * @return ImportId
     */
    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    /**
     * @return AttributeCode
     */
    public function getCode(): AttributeCode
    {
        return $this->code;
    }

    /**
     * @return OptionKey
     */
    public function getKey(): OptionKey
    {
        return $this->key;
    }

    /**
     * @return TranslatableString
     */
    public function getTranslation(): TranslatableString
    {
        return $this->translation;
    }
}
