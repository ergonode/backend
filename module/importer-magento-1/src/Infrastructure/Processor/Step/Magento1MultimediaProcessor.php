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

/**
 */
class Magento1MultimediaProcessor implements Magento1ProcessorStepInterface
{
    /**
     * @var KernelInterface
     */
    private KernelInterface $kernel;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param KernelInterface     $kernel
     * @param CommandBusInterface $commandBus
     */
    public function __construct(KernelInterface $kernel, CommandBusInterface $commandBus)
    {
        $this->kernel = $kernel;
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
        if(!$source->importMultimedia()) {
            return ;
        }

        $cacheDir = sprintf('%s/import-%s', $this->kernel->getCacheDir(), $import->getId()->getValue());
        $result = [];
        foreach ($products as $product) {
            $default = $product->get('default');
            if (array_key_exists('image', $default) && $default['image'] !== null) {
                $images = explode(',', $default['image']);
                foreach ($images as $image) {
                    $result[$image] = $source->getUrl() . $image;
                }
            }
        }

        $i = 0;
        foreach ($result as $image => $url) {
            $i++;
            $content = file_get_contents($url);
            $filePath = sprintf('%s/%s', $cacheDir, $image);
            $this->saveFile($filePath, $content);
            $file = new File($filePath);
            $multimediaId = MultimediaId::fromKey($url);
            $command = new AddMultimediaCommand($multimediaId, $file);
            $this->commandBus->dispatch($command);
        }

        echo print_r(sprintf('SEND %s Images', $i), true) . PHP_EOL;
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
