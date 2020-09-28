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
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Importer\Domain\Command\Import\ProcessImportCommand;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Importer\Infrastructure\Action\MultimediaImportAction;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;

/**
 */
class Magento1MultimediaProcessor implements Magento1ProcessorStepInterface
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var string[]
     */
    private array $media;

    /**
     * @param CommandBusInterface $commandBus
     */
    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
        $this->media = [];
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
        if (!$source->import(Magento1CsvSource::MULTIMEDIA)) {
            return;
        }

        $default = $product->get('default');
        if (array_key_exists('image', $default) && $default['image'] !== null) {
            $images = explode(',', $default['image']);
            foreach ($images as $image) {
                $this->processImage($source, $import, $image);
            }
        }

        foreach ($source->getLanguages() as $key => $language) {
            if ($product->has($key)) {
                $version = $product->get($key);
                if (array_key_exists('image', $version) && $version['image'] !== null) {
                    $images = explode(',', $version['image']);
                    foreach ($images as $image) {
                        $this->processImage($source, $import, $image);
                    }
                }
            }
        }
    }

    /**
     * @param Magento1CsvSource $source
     * @param Import            $import
     * @param string            $image
     */
    private function processImage(Magento1CsvSource $source, Import $import, string $image): void
    {
        $url = sprintf('%s/media/catalog/product%s', $source->getHost(), $image);
        $filename = pathinfo($image, PATHINFO_BASENAME);

        if (!array_key_exists($url, $this->media) && (strpos($url, 'no_selection') === false)) {
            $record = new Record();
            $record->set('name', $filename);
            $record->set('url', $url);
            $this->media[$url] = $url;

            $command = new ProcessImportCommand(
                $import->getId(),
                $record,
                MultimediaImportAction::TYPE
            );
            $this->commandBus->dispatch($command, true);
        }
    }
}
