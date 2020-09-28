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
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Transformer\Infrastructure\Formatter\SlugFormatter;
use Ramsey\Uuid\Uuid;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Importer\Infrastructure\Action\CategoryImportAction;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;

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
     * @var string[]
     */
    private array $categories;

    /**
     * @param CommandBusInterface $commandBus
     */
    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
        $this->categories = [];
    }

    /**
     * @param Import            $import
     * @param ProductModel      $product
     * @param Transformer       $transformer
     * @param Magento1CsvSource $source
     */
    public function process(
        Import $import,
        ProductModel $product,
        Transformer $transformer,
        Magento1CsvSource $source
    ): void {
        $default = $product->get('default');
        if (array_key_exists('esa_categories', $default) && $default['esa_categories'] !== '') {
            $categories = explode(',', $default['esa_categories']);
            $codes = [];
            foreach ($categories as $category) {
                $category = explode('/', $category);
                $code = end($category);

                if ('' !== $code) {
                    $uuid = Uuid::uuid5(self::UUID, $code)->toString();
                    $slug = SlugFormatter::format(sprintf('%s_%s', $code, $uuid));
                    $codes[] = $slug;
                    if (!array_key_exists($code, $this->categories)) {
                        $record = new Record();
                        $record->set('id', $code);
                        $record->set('code', $slug);
                        $record->set('name', end($category), $source->getDefaultLanguage());
                        $this->categories[$code] = $slug;

                        $this->send($import->getId(), $record);
                    }

                    $default['esa_categories'] = implode(',', $codes);
                    $product->set('default', $default);
                }
            }
        }
    }

    /**
     * @param ImportId $importId
     * @param Record   $category
     */
    private function send(ImportId $importId, Record $category): void
    {
        $command = new ProcessImportCommand(
            $importId,
            $category,
            CategoryImportAction::TYPE
        );

        $this->commandBus->dispatch($command, true);
    }
}
