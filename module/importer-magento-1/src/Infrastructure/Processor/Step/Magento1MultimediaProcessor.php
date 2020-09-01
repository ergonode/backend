<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor\Step;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\ValueObject\Progress;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Importer\Domain\Command\Import\ProcessImportCommand;
use Ergonode\Transformer\Domain\Model\Record;
use Ramsey\Uuid\Uuid;
use Ergonode\Importer\Infrastructure\Action\MultimediaImportAction;

/**
 */
class Magento1MultimediaProcessor implements Magento1ProcessorStepInterface
{
    private const NAMESPACE = 'e1f84ee9-14f2-4e52-981a-b6b82006ada8';

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
     * @param array             $products
     * @param Transformer       $transformer
     * @param Magento1CsvSource $source
     * @param Progress          $steps
     *
     * @return int
     */
    public function process(
        Import $import,
        array $products,
        Transformer $transformer,
        Magento1CsvSource $source,
        Progress $steps
    ): int {
        if (!$source->import(Magento1CsvSource::MULTIMEDIA)) {
            return 0;
        }

        $result = [];
        foreach ($products as $product) {
            $default = $product->get('default');
            if (array_key_exists('image', $default) && $default['image'] !== null) {
                $images = explode(',', $default['image']);
                foreach ($images as $image) {
                    $url = sprintf('%s/media/catalog/product%s', $source->getHost(), $image);
                    if (strpos($url, 'no_selection') === false) {
                        $uuid = Uuid::uuid5(self::NAMESPACE, $url);
                        $record = new Record();
                        $record->set('name', $image);
                        $record->set('id', $uuid->toString());
                        $record->set('url', $url);
                        $result[] = $record;
                    }
                }
            }
        }

        $i = 0;
        $count = count($result);
        foreach ($result as $images => $image) {
            $i++;
            $records = new Progress($i, $count);
            $command = new ProcessImportCommand(
                $import->getId(),
                $steps,
                $records,
                $image,
                MultimediaImportAction::TYPE
            );
            $this->commandBus->dispatch($command, true);
        }

        return $count;
    }
}
