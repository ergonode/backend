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
class ImportMultimediaFromWebCommand implements DomainCommandInterface
{
    /**
     * @var ImportId
     */
    private ImportId $importId;

    /**
     * @var string
     */
    private string $url;

    /**
     * @var string
     */
    private string $name;

    /**
     * @param ImportId $importId
     * @param string   $url
     * @param string   $name
     */
    public function __construct(ImportId $importId, string $url, string $name)
    {
        $this->importId = $importId;
        $this->url = $url;
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
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
