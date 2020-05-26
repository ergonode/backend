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
     * @param AbstractExportProfile|FileExportProfile $profile
     */
    public function process(AbstractExportProfile $profile): void
    {
        $writer = $this->provider->provide($profile->getFormat());
        $attributes = array_values($this->query->getDictionary());
        sort($attributes);
        
        $filename = sprintf('export.%s', $writer->getType());

        $this->storage->open($filename);
        $this->storage->append($writer->end($attributes));
        $this->storage->close();
    }
}
