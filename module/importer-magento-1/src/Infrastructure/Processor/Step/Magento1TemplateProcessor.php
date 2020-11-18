<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor\Step;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\Importer\Domain\Command\Import\ImportTemplateCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;

class Magento1TemplateProcessor implements Magento1ProcessorStepInterface
{
    private CommandBusInterface $commandBus;

    /**
     * @var string[]
     */
    private array $templates;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
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
            $this->templates[$template] = $template;
            $command = new ImportTemplateCommand(
                $import->getId(),
                $template
            );
            $this->commandBus->dispatch($command, true);
        }
    }
}
