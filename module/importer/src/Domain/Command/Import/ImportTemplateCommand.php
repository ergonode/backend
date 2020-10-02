<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

/**
 */
class ImportTemplateCommand implements DomainCommandInterface
{
    /**
     * @var ImportId
     */
    private ImportId $importId;

    /**
     * @var string
     */
    private string $code;

    /**
     * @param ImportId $importId
     * @param string   $code
     */
    public function __construct(ImportId $importId, string $code)
    {
        $this->importId = $importId;
        $this->code = $code;
    }

    /**
     * @return ImportId
     */
    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
}
