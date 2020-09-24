<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor;

use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Webmozart\Assert\Assert;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;
use Ergonode\Importer\Infrastructure\Processor\SourceImportProcessorInterface;
use Ergonode\Importer\Domain\Entity\ImportError;
use Ergonode\Importer\Domain\Repository\ImportErrorRepositoryInterface;
use Ergonode\Reader\Infrastructure\Exception\ReaderException;
use Ergonode\Importer\Infrastructure\Exception\ImportException;

/**
 */
class StartMagento1ImportProcess implements SourceImportProcessorInterface
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
     * @var ImportErrorRepositoryInterface
     */
    private ImportErrorRepositoryInterface $importErrorRepository;

    /**
     * @var Magento1ProductsProvider
     */
    private Magento1ProductsProvider $provider;

    /**
     * @var Magento1ProcessorStepInterface[]
     */
    private array $steps;

    /**
     * @param SourceRepositoryInterface        $sourceRepository
     * @param TransformerRepositoryInterface   $transformerRepository
     * @param ImportErrorRepositoryInterface   $importErrorRepository
     * @param Magento1ProductsProvider         $provider
     * @param Magento1ProcessorStepInterface[] $steps
     */
    public function __construct(
        SourceRepositoryInterface $sourceRepository,
        TransformerRepositoryInterface $transformerRepository,
        ImportErrorRepositoryInterface $importErrorRepository,
        Magento1ProductsProvider $provider,
        array $steps
    ) {
        $this->sourceRepository = $sourceRepository;
        $this->transformerRepository = $transformerRepository;
        $this->importErrorRepository = $importErrorRepository;
        $this->provider = $provider;
        $this->steps = $steps;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return $type === Magento1CsvSource::TYPE;
    }

    /**
     * @param Import $import
     *
     * @throws \ReflectionException
     */
    public function start(Import $import): void
    {
        /** @var Magento1CsvSource $source */
        $source = $this->sourceRepository->load($import->getSourceId());
        Assert::notNull($source);
        $transformer = $this->transformerRepository->load($import->getTransformerId());
        Assert::notNull($transformer);

        $message = null;

        try {
            $products = $this->provider->getProducts($import, $transformer);

            foreach ($products as $product) {
                foreach ($this->steps as $step) {
                    $step->process($import, $product, $transformer, $source);
                }
            }
//            foreach ($this->steps as $step) {
//                $records = $step->process($import, $products, $transformer, $source);
//                $import->addRecords($records);
//            }
        } catch (ImportException|ReaderException $exception) {
            $message = $exception->getMessage();
        } catch (\Throwable $exception) {
            $message = 'Import processing error';
        }

        if ($message) {
            echo print_r($exception->getMessage(), true);
            echo print_r($exception->getTraceAsString(), true);

            die('end');
            $error = new ImportError($import->getId(), $message);
            $import->stop();
            $this->importErrorRepository->add($error);
        }
    }
}
