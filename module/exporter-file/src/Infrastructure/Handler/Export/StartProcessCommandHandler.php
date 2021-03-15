<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
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
use Ergonode\ExporterFile\Infrastructure\Builder\ExportTemplateElementBuilder;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportAttributeBuilder;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportOptionBuilder;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportCategoryBuilder;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportTemplateBuilder;

class StartProcessCommandHandler
{
    private ExportRepositoryInterface $repository;

    private TempFileStorage $storage;

    private ExportProductBuilder $productBuilder;

    private ExportTemplateElementBuilder $templateElementBuilder;

    private ExportAttributeBuilder $attributeBuilder;

    private ExportOptionBuilder $optionBuilder;

    private ExportCategoryBuilder $categoryBuilder;

    private ExportTemplateBuilder $templateBuilder;

    public function __construct(
        ExportRepositoryInterface $repository,
        TempFileStorage $storage,
        ExportProductBuilder $productBuilder,
        ExportTemplateElementBuilder $templateElementBuilder,
        ExportAttributeBuilder $attributeBuilder,
        ExportOptionBuilder $optionBuilder,
        ExportCategoryBuilder $categoryBuilder,
        ExportTemplateBuilder $templateBuilder
    ) {
        $this->repository = $repository;
        $this->storage = $storage;
        $this->productBuilder = $productBuilder;
        $this->templateElementBuilder = $templateElementBuilder;
        $this->attributeBuilder = $attributeBuilder;
        $this->optionBuilder = $optionBuilder;
        $this->categoryBuilder = $categoryBuilder;
        $this->templateBuilder = $templateBuilder;
    }

    public function __invoke(StartFileExportCommand $command): void
    {
        $export = $this->repository->load($command->getExportId());
        Assert::isInstanceOf($export, Export::class);
        $export->start();
        $this->repository->save($export);

        $products = $this->productBuilder->header();
        $templatesElements = $this->templateElementBuilder->header();
        $attributes = $this->attributeBuilder->header();
        $categories = $this->categoryBuilder->header();
        $options = $this->optionBuilder->header();
        $templates = $this->templateBuilder->header();

        $this->storage->create(sprintf('%s/attributes.csv', $command->getExportId()->getValue()));
        $this->storage->append([implode(',', $attributes).PHP_EOL]);
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
        $this->storage->create(sprintf('%s/templates.csv', $command->getExportId()->getValue()));
        $this->storage->append([implode(',', $templates).PHP_EOL]);
        $this->storage->close();
        $this->storage->create(sprintf('%s/templates_elements.csv', $command->getExportId()->getValue()));
        $this->storage->append([implode(',', $templatesElements).PHP_EOL]);
        $this->storage->close();
    }
}
