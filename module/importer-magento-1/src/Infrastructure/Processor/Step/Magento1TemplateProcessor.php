<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor\Step;

use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\Importer\Domain\Command\Import\ImportTemplateCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

class Magento1TemplateProcessor implements Magento1ProcessorStepInterface
{
    private CommandBusInterface $commandBus;

    private ImportRepositoryInterface $importRepository;

    /**
     * @var string[]
     */
    private array $templates;

    public function __construct(CommandBusInterface $commandBus, ImportRepositoryInterface $importRepository)
    {
        $this->commandBus = $commandBus;
        $this->importRepository = $importRepository;
        $this->templates = [];
    }

    /**
     * @param AbstractAttribute[] $attributes
     */
    public function process(
        Import $import,
        ProductModel $product,
        Magento1CsvSource $source,
        array $attributes
    ): void {
        $template = $product->getTemplate();
        if (!array_key_exists($template, $this->templates)) {
            $id = ImportLineId::generate();
            $this->templates[$template] = $template;
            $command = new ImportTemplateCommand(
                $id,
                $import->getId(),
                $template
            );
            $this->importRepository->addLine($id, $import->getId(), 'TEMPLATE');
            $this->commandBus->dispatch($command, true);
        }
    }
}
