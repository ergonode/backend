<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor\Step;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\ProcessImportCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\ValueObject\Progress;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Transformer\Infrastructure\Action\TemplateImportAction;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Transformer\Domain\Entity\Transformer;

/**
 */
class Magento1TemplateProcessor implements Magento1ProcessorStepInterface
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param CommandBusInterface $commandBus
     */
    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param Import            $import
     * @param ProductModel[]    $products
     * @param Transformer       $transformer
     * @param Magento1CsvSource $source
     * @param Progress          $steps
     */
    public function process(
        Import $import,
        array $products,
        Transformer $transformer,
        Magento1CsvSource $source,
        Progress $steps
    ): void {
        $templates = [];
        foreach ($products as $sku => $product) {
            $default = $product->get('default');
            if (array_key_exists('esa_template', $default)) {
                $type = $default['esa_template'];
                if (!array_key_exists($type, $templates)) {
                    $templates[$type] = new Record();
                    $templates[$type]->set('code', new StringValue($type));
                    $templates[$type]->set(
                        'name',
                        new TranslatableStringValue(
                            new TranslatableString([$source->getDefaultLanguage()->getCode() => $type])
                        )
                    );
                }
            }
        }

        $i = 0;
        $count = count($templates);
        foreach ($templates as $template) {
            $i++;
            $records = new Progress($i, $count);
            $command = new ProcessImportCommand(
                $import->getId(),
                $steps,
                $records,
                $template,
                TemplateImportAction::TYPE
            );
            $this->commandBus->dispatch($command);
        }
    }
}
