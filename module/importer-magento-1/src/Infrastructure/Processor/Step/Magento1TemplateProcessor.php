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
     * @param ImportId $id
     * @param string[] $rows
     * @param Language $language
     */
    public function process(ImportId $id, array $rows, Language $language): void
    {
        $result = [];
        foreach ($rows as $row) {
            if ($row['sku'] && $row['_attribute_set']) {
                $type = $row['_attribute_set'];
                $result[$type] = new Record();
                $result[$type]->set('code', new StringValue($type));
                $result[$type]->set('name', new TranslatableStringValue(new TranslatableString([$language->getCode() => $type])));
            }
        }

        $i = 0;
        foreach ($result as $template) {
            $i++;
            $command = new ProcessImportCommand($id, $i, $template, TemplateImportAction::TYPE);
            $this->commandBus->dispatch($command);
        }
    }
}
