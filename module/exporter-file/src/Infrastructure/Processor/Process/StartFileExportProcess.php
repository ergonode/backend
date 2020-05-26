<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Processor\Process;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\ExporterFile\Infrastructure\Provider\WriterProvider;
use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\ExporterFile\Domain\Entity\FileExportProfile;
use Ergonode\ExporterFile\Infrastructure\Storage\FileStorage;

/**
 */
class StartFileExportProcess
{
    /**
     * @var AttributeQueryInterface
     */
    private AttributeQueryInterface $attributeQuery;

    /**
     * @var WriterProvider
     */
    private WriterProvider $provider;

    /**
     * @var FileStorage
     */
    private FileStorage $storage;

    /**
     * @param AttributeQueryInterface $attributeQuery
     * @param WriterProvider          $provider
     * @param FileStorage             $storage
     */
    public function __construct(AttributeQueryInterface $attributeQuery, WriterProvider $provider, FileStorage $storage)
    {
        $this->attributeQuery = $attributeQuery;
        $this->provider = $provider;
        $this->storage = $storage;
    }

    /**
     * @param AbstractExportProfile|FileExportProfile $profile
     */
    public function process(AbstractExportProfile $profile): void
    {
        $writer = $this->provider->provide($profile->getFormat());

        $attributes = array_values($this->attributeQuery->getDictionary());
        sort($attributes);

        $filename = sprintf('export.%s', $writer->getType());

        $this->storage->create($filename);
        $this->storage->append($writer->start($attributes));
        $this->storage->close();
    }
}
