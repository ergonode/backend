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
use Ergonode\Transformer\Infrastructure\Action\CategoryImportAction;
use Ergonode\Transformer\Infrastructure\Formatter\SlugFormatter;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

/**
 */
class Magento1CategoryProcessor implements Magento1ProcessorStepInterface
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
        $result = [];
        foreach ($products as $sku => $product) {
            $default = $product->get('default');
            if (array_key_exists('esa_categories', $default)) {
                if ($default['esa_categories'] !== '') {
                    $categories = explode(',', $default['esa_categories']);
                    foreach ($categories as $category) {
                        $category = explode('/', $category);
                        $code = end($category);
                        $name = [$defaultLanguage->getCode() => end($category)];
                        if (!array_key_exists($code, $result)) {
                            $record = new Record();
                            $record->set('code', new StringValue(SlugFormatter::format($code)));
                            $record->set('name', new TranslatableStringValue(new TranslatableString($name)));
                            $result[$code] = $record;
                        }
                    }
                }
            }
        }

        $i = 0;
        foreach ($result as $category) {
            $i++;
            $command = new ProcessImportCommand($import->getId(), $i, $category, CategoryImportAction::TYPE);
            $this->commandBus->dispatch($command);
        }
    }
}
