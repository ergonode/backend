<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Handler\Export;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Core\Infrastructure\Service\TempFileStorage;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use Ergonode\ExporterFile\Domain\Command\Export\StartFileExportCommand;
use Webmozart\Assert\Assert;

class StartProcessCommandHandler
{
    private ExportRepositoryInterface $repository;

    private TempFileStorage $storage;

    private AttributeQueryInterface $attributeQuery;

    public function __construct(
        ExportRepositoryInterface $repository,
        TempFileStorage $storage,
        AttributeQueryInterface $attributeQuery
    ) {
        $this->repository = $repository;
        $this->storage = $storage;
        $this->attributeQuery = $attributeQuery;
    }

    public function __invoke(StartFileExportCommand $command): void
    {
        $export = $this->repository->load($command->getExportId());
        Assert::isInstanceOf($export, Export::class);
        $export->start();
        $this->repository->save($export);
        $availableAttributes = array_values($this->attributeQuery->getDictionary());
        sort($availableAttributes);

        $attribute = ['_id', '_code', '_type', '_language', '_name', '_hint', '_placeholder', '_scope', '_parameters'];
        $categories = ['_id', '_code', '_name', '_language'];
        $products = array_merge(['_id', '_sku', '_type', '_language', '_template'], $availableAttributes);
        $options = ['_id', '_code', '_attribute_id', '_attribute_code', '_language', '_label'];
        $multimedia = ['_id', '_language', '_name', '_filename', '_extension', '_mime', '_alt', '_size'];
        $templates = ['_id', '_name', '_type', '_x', '_y', '_width', '_height', '_properties'];
        $this->storage->create(sprintf('%s/attributes.csv', $command->getExportId()->getValue()));
        $this->storage->append([implode(',', $attribute).PHP_EOL]);
        $this->storage->close();
        $this->storage->create(sprintf('%s/categories.csv', $command->getExportId()->getValue()));
        $this->storage->append([implode(',', $categories).PHP_EOL]);
        $this->storage->close();
        $this->storage->create(sprintf('%s/products.csv', $command->getExportId()->getValue()));
        $this->storage->append([implode(',', $products).PHP_EOL]);
        $this->storage->close();
        $this->storage->create(sprintf('%s/options.csv', $command->getExportId()->getValue()));
        $this->storage->append([implode(',', $options).PHP_EOL]);
        $this->storage->close();
        $this->storage->create(sprintf('%s/multimedia.csv', $command->getExportId()->getValue()));
        $this->storage->append([implode(',', $multimedia).PHP_EOL]);
        $this->storage->close();
        $this->storage->create(sprintf('%s/templates.csv', $command->getExportId()->getValue()));
        $this->storage->append([implode(',', $templates).PHP_EOL]);
        $this->storage->close();
    }
}
