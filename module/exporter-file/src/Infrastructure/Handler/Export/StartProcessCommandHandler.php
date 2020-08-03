<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Handler\Export;

use Ergonode\ExporterFile\Infrastructure\Storage\FileStorage;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\ExporterFile\Domain\Command\Export\StartFileExportCommand;
use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Exporter\Domain\Entity\Export;

/**
 */
class StartProcessCommandHandler
{
    /**
     * @var ExportRepositoryInterface
     */
    private ExportRepositoryInterface $repository;

    /**
     * @var FileStorage
     */
    private FileStorage $storage;

    /**
     * @var AttributeQueryInterface
     */
    private AttributeQueryInterface $attributeQuery;

    /**
     * @param ExportRepositoryInterface $repository
     * @param FileStorage               $storage
     * @param AttributeQueryInterface   $attributeQuery
     */
    public function __construct(
        ExportRepositoryInterface $repository,
        FileStorage $storage,
        AttributeQueryInterface $attributeQuery
    ) {
        $this->repository = $repository;
        $this->storage = $storage;
        $this->attributeQuery = $attributeQuery;
    }

    /**
     * @param StartFileExportCommand $command
     */
    public function __invoke(StartFileExportCommand $command)
    {
        $export = $this->repository->load($command->getExportId());
        Assert::isInstanceOf($export, Export::class);
        $export->start();
        $this->repository->save($export);
        $availableAttributes = array_values($this->attributeQuery->getDictionary());
        sort($availableAttributes);

        $attribute = ['_id', '_code', '_type', '_language', '_name', '_hint', '_placeholder'];
        $categories = ['_id', '_code', '_name', '_language'];
        $products = ['_id', '_sku', '_type', '_language', '_template'] + $availableAttributes;
        $this->storage->create(sprintf('%s/attributes.csv', $command->getExportId()->getValue()));
        $this->storage->append([implode(',', $attribute).PHP_EOL]);
        $this->storage->close();
        $this->storage->create(sprintf('%s/categories.csv', $command->getExportId()->getValue()));
        $this->storage->append([implode(',', $categories).PHP_EOL]);
        $this->storage->close();
        $this->storage->create(sprintf('%s/products.csv', $command->getExportId()->getValue()));
        $this->storage->append([implode(',', $products).PHP_EOL]);
        $this->storage->close();
    }
}
