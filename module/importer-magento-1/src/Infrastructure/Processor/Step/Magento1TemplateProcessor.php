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
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Transformer\Infrastructure\Action\TemplateImportAction;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;

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
     * @param Import         $import
     * @param ProductModel[] $products
     * @param Language       $defaultLanguage
     */
    public function process(Import $import, array $products, Language $defaultLanguage): void
    {
        $templates = [];
        foreach ($products as $sku => $product) {
            $default = $product->get('default', true);
            if (array_key_exists('_attribute_set', $default)) {
                $type = $default['_attribute_set'];
                if (!array_key_exists($type, $templates)) {
                    $templates[$type] = new Record();
                    $templates[$type]->set('code', new StringValue($type));
                    $templates[$type]->set('name', new TranslatableStringValue(
                            new TranslatableString([$defaultLanguage->getCode() => $type]))
                    );
                }
            }
        }

        $i = 0;
        foreach ($templates as $template) {
            $i++;
            $command = new ProcessImportCommand($import->getId(), $i, $template, TemplateImportAction::TYPE);
            $this->commandBus->dispatch($command);
        }
    }
}
