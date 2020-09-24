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
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Importer\Infrastructure\Action\AttributeImportAction;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;

/**
 */
class Magento1AttributeProcessor implements Magento1ProcessorStepInterface
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
        $result = [];
        foreach ($transformer->getAttributes() as $field => $converter) {
            $attributeCode = new AttributeCode($field);
            $type = $transformer->getAttributeType($field);
            $record = new Record();
            $record->set('code', $attributeCode->getValue());
            $record->set('type', $type);
            $multilingual = $transformer->isAttributeMultilingual($field) ? '1' : '0';
            $record->set('multilingual', $multilingual);
            $record->set('label', $field, $source->getDefaultLanguage());

            $result[] = $record;
        }

        $count = count($result);
        foreach ($result as $attribute) {
            $command = new ProcessImportCommand(
                $import->getId(),
                $attribute,
                AttributeImportAction::TYPE
            );
            $this->commandBus->dispatch($command, true);
        }
    }
}
