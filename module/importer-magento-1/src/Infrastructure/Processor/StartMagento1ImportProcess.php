<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\ProcessImportCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\ImporterMagento2\Domain\Entity\Magento2CsvSource;
use Ergonode\Reader\Infrastructure\Provider\ReaderProcessorProvider;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Infrastructure\Action\ProductImportAction;
use Webmozart\Assert\Assert;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Transformer\Infrastructure\Provider\ConverterMapperProvider;
use Ergonode\Importer\Domain\Entity\ImportId;

/**
 */
class StartMagento1ImportProcess
{
    /**
     * @var SourceRepositoryInterface
     */
    private SourceRepositoryInterface $sourceRepository;

    /**
     * @var TransformerRepositoryInterface
     */
    private TransformerRepositoryInterface $transformerRepository;

    /**
     * @var ReaderProcessorProvider
     */
    private ReaderProcessorProvider $provider;

    /**
     * @var string
     */
    private string $directory;

    /**
     * @var Magento1ProcessorStepInterface[]
     */
    private array $steps;

    /**
     * @param SourceRepositoryInterface      $sourceRepository
     * @param TransformerRepositoryInterface $transformerRepository
     * @param ReaderProcessorProvider        $provider
     * @param string                         $directory
     * @param Magento1ProcessorStepInterface ...$steps
     */
    public function __construct(
        SourceRepositoryInterface $sourceRepository,
        TransformerRepositoryInterface $transformerRepository,
        ReaderProcessorProvider $provider,
        string $directory,
        Magento1ProcessorStepInterface ...$steps
    ) {
        Assert::allIsInstanceOf($steps, Magento1ProcessorStepInterface::class);

        $this->sourceRepository = $sourceRepository;
        $this->transformerRepository = $transformerRepository;
        $this->provider = $provider;
        $this->directory = $directory;
        $this->steps = $steps;
    }

    /**
     * @param Import $import
     */
    public function start(Import $import): void
    {

        $language = new Language(Language::EN);
        $importId = $import->getId();

        try {
            $start = microtime(true);
            /** @var Magento2CsvSource $source */
            $source = $this->sourceRepository->load($import->getSourceId());
            Assert::notNull($source);

            $transformer = $this->transformerRepository->load($import->getTransformerId());

            Assert::notNull($transformer);

            $file = $source->getFile();
            $filename = \sprintf('%s%s', $this->directory, $file);
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $fileReader = $this->provider->provide($extension);

            $rows = [];
            $fileReader->open($filename, $source->getConfiguration());
            foreach ($fileReader->read() as $row) {
                $rows[] = $row;
            }

            foreach ($this->steps as $step) {
                $step->process($importId, $rows, $language);
            }

            $end = microtime(true);

            echo ($end - $start) . PHP_EOL;
        } catch (\Throwable $exception) {
            echo $exception->getMessage() . PHP_EOL;
            echo print_r($exception->getTraceAsString(), true);
            die;
        }
    }

    /**
     * @param array $versions
     *
     * @return array
     */
    public function merge(array $versions): array
    {
        $product = [];
        foreach ($versions as $version) {
            foreach ($version as $key => $value) {
                if (null !== $value[$key]) {
                    if (!array_key_exists($key, $product)) {
                        $product[$key] = $value;
                    } else {
                        $product[$key] .= ',' . $value;
                    }
                }
            }
        }

        return $product;
    }
}
