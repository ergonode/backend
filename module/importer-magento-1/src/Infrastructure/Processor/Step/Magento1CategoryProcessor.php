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
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Transformer\Infrastructure\Action\CategoryImportAction;
use Ergonode\Transformer\Infrastructure\Formatter\SlugFormatter;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ramsey\Uuid\Uuid;
use Ergonode\Transformer\Domain\Entity\Transformer;

/**
 */
class Magento1CategoryProcessor implements Magento1ProcessorStepInterface
{
    private const UUID = '5bfd053c-e39b-45f9-87a7-6ca1cc9d9830';

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
     */
    public function process(Import $import, array $products, Transformer $transformer, Magento1CsvSource $source): void
    {
        $result = [];
        foreach ($products as $sku => $product) {
            $default = $product->get('default');

            if (array_key_exists('esa_categories', $default) && $default['esa_categories'] !== '') {
                $categories = explode(',', $default['esa_categories']);
                foreach ($categories as $category) {
                    $category = explode('/', $category);
                    $code = end($category);
                    if ('' !== $code) {
                        $name = [$source->getDefaultLanguage()->getCode() => end($category)];
                        if (!array_key_exists($code, $result)) {
                            $record = new Record();
                            $slug = SlugFormatter::format(Uuid::uuid5(self::UUID, $code)->toString());
                            $record->set('id', new StringValue($code));
                            $record->set('code', new StringValue($slug));
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

        echo print_r(sprintf('SEND %s Categories', $i), true).PHP_EOL;
    }
}
