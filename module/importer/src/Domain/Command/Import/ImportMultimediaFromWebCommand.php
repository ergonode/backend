<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

class ImportMultimediaFromWebCommand implements DomainCommandInterface
{
    private ImportId $importId;

    private string $url;

    private string $name;

    public function __construct(ImportId $importId, string $url, string $name)
    {
        $this->importId = $importId;
        $this->url = $url;
        $this->name = $name;
    }

    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
