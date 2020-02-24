<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor\Step;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\Multimedia\Domain\Command\AddMultimediaCommand;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Symfony\Component\HttpFoundation\File\File;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Symfony\Component\HttpKernel\KernelInterface;
use Ergonode\Importer\Domain\Command\Import\ProcessImportCommand;
use Ergonode\Transformer\Infrastructure\Action\CategoryImportAction;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Transformer\Infrastructure\Action\MultimediaImportAction;

/**
 */
class Magento1MultimediaProcessor implements Magento1ProcessorStepInterface
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
     *
     * @throws \Exception
     */
    public function process(Import $import, array $products, Transformer $transformer, Magento1CsvSource $source): void
    {
        if (!$source->import(Magento1CsvSource::MULTIMEDIA)) {
            return;
        }

        $result = [];
        foreach ($products as $product) {
            $default = $product->get('default');
            if (array_key_exists('image', $default) && $default['image'] !== null) {
                $images = explode(',', $default['image']);
                foreach ($images as $image) {
                    $record = new Record();
                    $record->set('name', new StringValue($image));
                    $record->set('id', new StringValue(MultimediaId::generate()->getValue()));
                    $url = sprintf('%s/%s', $source->getHost(), $image);
                    $record->set('url', new StringValue(str_replace('//', '/', $url)));
                    $result[] = $record;
                }
            }
        }

        $i = 0;
        foreach ($result as $images => $image) {
            $i++;
            $command = new ProcessImportCommand($import->getId(), $i, $image, MultimediaImportAction::TYPE);
            $this->commandBus->dispatch($command);
        }
    }

    /**
     * @param $dir
     * @param $contents
     */
    public function saveFile($dir, $contents): void
    {
        $parts = explode('/', $dir);
        $file = array_pop($parts);
        $dir = '';
        foreach ($parts as $part) {
            if (!is_dir($dir .= "/$part")) {
                mkdir($dir);
            }
        }

        file_put_contents("$dir/$file", $contents);
    }
}
