<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Processor\Process;

use Ergonode\ExporterFile\Infrastructure\Provider\WriterProvider;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\ExporterFile\Domain\Entity\FileExportProfile;
use Ergonode\ExporterFile\Infrastructure\Storage\FileStorage;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

/**
 */
class EndFileExportProcess
{
    /**
     * @var AttributeQueryInterface
     */
    private AttributeQueryInterface $query;
    
    /**
     * @var WriterProvider
     */
    private WriterProvider $provider;

    /**
     * @var FileStorage
     */
    private FileStorage $storage;

    /**
     * @param AttributeQueryInterface $query
     * @param WriterProvider          $provider
     * @param FileStorage             $storage
     */
    public function __construct(AttributeQueryInterface $query, WriterProvider $provider, FileStorage $storage)
    {
        $this->query = $query;
        $this->provider = $provider;
        $this->storage = $storage;
    }

    /**
     * @param ExportId                                $id
     * @param AbstractExportProfile|FileExportProfile $profile
     */
    public function process(ExportId $id, AbstractExportProfile $profile): void
    {
        $writer = $this->provider->provide($profile->getFormat());
        $attributes = array_values($this->query->getDictionary());
        sort($attributes);
        
        $filename = sprintf('%s.%s', $id->getValue(), $writer->getType());

        $this->storage->open($filename);
        $this->storage->append($writer->end($attributes));
        $this->storage->close();
    }
}
