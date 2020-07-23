<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Processor\Process;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\ExporterFile\Infrastructure\Provider\WriterProvider;
use Ergonode\ExporterFile\Infrastructure\Storage\FileStorage;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

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
     * @param ExportId                          $id
     * @param AbstractChannel|FileExportChannel $channel
     */
    public function process(ExportId $id, AbstractChannel $channel): void
    {
        $writer = $this->provider->provide($channel->getFormat());

        $attributes = array_values($this->attributeQuery->getDictionary());
        sort($attributes);

        $filename = sprintf('%s.%s', $id->getValue(), $writer->getType());

        $this->storage->create($filename);
        $this->storage->append($writer->start($attributes));
        $this->storage->close();
    }
}
