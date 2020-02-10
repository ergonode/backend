<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor\Step;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\ProcessImportCommand;
use Ergonode\Importer\Domain\Entity\ImportId;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Transformer\Infrastructure\Action\CategoryImportAction;
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
     * @param ImportId $id
     * @param string[] $rows
     * @param Language $language
     */
    public function process(ImportId $id, array $rows, Language $language): void
    {
        $defaultLanguage = $language->getCode();
        $result = [];
        foreach ($rows as $row) {
            if ($row['sku'] && $row['_category']) {
                $categories = explode('/', $row['_category']);
                foreach ($categories as $category) {
                    $code = $category;
                    if (!array_key_exists($code, $result)) {
                        $language = !empty($row['_store']) ? $row['_store'] : $defaultLanguage;
                        $name = [$language => $category];
                        $record = new Record();
                        $record->set('code', new StringValue($code));
                        $record->set('name', new TranslatableStringValue(new TranslatableString($name)));
                        $result[$code] = $record;
                    }
                }
            }
        }

        $i = 0;
        foreach ($result as $category) {
            $i++;
            $command = new ProcessImportCommand($id, $i, $category, CategoryImportAction::TYPE);
            $this->commandBus->dispatch($command);
        }
    }
}
