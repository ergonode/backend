<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

class ImportCategoryCommand implements DomainCommandInterface
{
    /**
     * @var ImportId
     */
    private ImportId $importId;

    /**
     * @var CategoryCode
     */
    private CategoryCode $code;

    /**
     * @var TranslatableString
     */
    private TranslatableString $name;

    /**
     * @param ImportId           $importId
     * @param CategoryCode       $code
     * @param TranslatableString $name
     */
    public function __construct(ImportId $importId, CategoryCode $code, TranslatableString $name)
    {
        $this->importId = $importId;
        $this->code = $code;
        $this->name = $name;
    }

    /**
     * @return ImportId
     */
    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    /**
     * @return CategoryCode
     */
    public function getCode(): CategoryCode
    {
        return $this->code;
    }

    /**
     * @return TranslatableString
     */
    public function getName(): TranslatableString
    {
        return $this->name;
    }
}
