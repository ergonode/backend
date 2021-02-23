<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Handler\Export;

use Ergonode\Core\Infrastructure\Service\TempFileStorage;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use Ergonode\ExporterFile\Domain\Command\Export\StartFileExportCommand;
use Webmozart\Assert\Assert;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportProductBuilder;
use Ergonode\ExporterFile\Infrastructure\Builder\TemplateElementBuilder;

class StartProcessCommandHandler
{
    private ExportRepositoryInterface $repository;

    private TempFileStorage $storage;

    private ExportProductBuilder $productBuilder;

    private TemplateElementBuilder $templateElementBuilder;

    public function __construct(
        ExportRepositoryInterface $repository,
        TempFileStorage $storage,
        ExportProductBuilder $productBuilder,
        TemplateElementBuilder $templateElementBuilder
    ) {
        $this->repository = $repository;
        $this->storage = $storage;
        $this->productBuilder = $productBuilder;
        $this->templateElementBuilder = $templateElementBuilder;
    }

    public function __invoke(StartFileExportCommand $command): void
    {
        $export = $this->repository->load($command->getExportId());
        Assert::isInstanceOf($export, Export::class);
        $export->start();
        $this->repository->save($export);

        $products = $this->productBuilder->header();
        $templatesElements = $this->templateElementBuilder->header();

        $attribute = ['_code', '_type', '_language', '_name', '_hint', '_placeholder', '_scope', '_parameters'];
        $categories = ['_code', '_name', '_language'];
        $options = ['_code', '_attribute_code', '_language', '_label'];
        $multimedia = ['_id', '_language', '_name', '_filename', '_extension', '_mime', '_alt', '_size'];
        $templates = ['_name'];

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
        $this->storage->create(sprintf('%s/templates_elements.csv', $command->getExportId()->getValue()));
        $this->storage->append([implode(',', $templatesElements).PHP_EOL]);
        $this->storage->close();
    }
}
