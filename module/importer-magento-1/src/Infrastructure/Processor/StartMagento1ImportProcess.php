<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor;

use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\Importer\Domain\ValueObject\Progress;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Webmozart\Assert\Assert;
use Ergonode\ImporterMagento1\Infrastructure\Reader\Magento1CsvReader;
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
    private ImportErrorRepositoryInterface $importLineRepository;

    /**
     * @var Magento1CsvReader
     */
    private Magento1CsvReader $reader;

    /**
     * @var Magento1ProcessorStepInterface[]
     */
    private array $steps;

    /**
     * @param SourceRepositoryInterface        $sourceRepository
     * @param TransformerRepositoryInterface   $transformerRepository
     * @param ImportErrorRepositoryInterface   $importLineRepository
     * @param Magento1CsvReader                $reader
     * @param Magento1ProcessorStepInterface[] $steps
     */
    public function __construct(
        SourceRepositoryInterface $sourceRepository,
        TransformerRepositoryInterface $transformerRepository,
        ImportErrorRepositoryInterface $importLineRepository,
        Magento1CsvReader $reader,
        array $steps
    ) {
        $this->sourceRepository = $sourceRepository;
        $this->transformerRepository = $transformerRepository;
        $this->importLineRepository = $importLineRepository;
        $this->reader = $reader;
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

        $result = [];
        $message = null;
        $this->reader->open($import);
        try {
            $product = $this->reader->read($transformer);

            while ($product !== null) {
                $result[] = $product;
                $product = $this->reader->read($transformer);
            }
        } catch (ImportException $exception) {
            $message = $exception->getMessage();
        } catch (ReaderException $exception) {
            $message = $exception->getMessage();
        } catch (\Throwable $exception) {
            $message = 'Import processing error';
        }
        $this->reader->close();
        if ($message) {
            $line = new ImportError($import->getId(), $message);
            $import->stop();
            $this->importLineRepository->add($line);

            return;
        }
        try {
            $count = count($this->steps);
            $i = 0;
            foreach ($this->steps as $step) {
                $i++;
                $steps = new Progress($i, $count);
                $records = $step->process($import, $result, $transformer, $source, $steps);
                $import->addRecords($records);
            }
        } catch (ImportException $exception) {
            $message = $exception->getMessage();
        } catch (\Throwable $exception) {
            $message = 'Import processing error';
        }

        if ($message) {
            $line = new ImportError($import->getId(), $message);
            $import->stop();
            $this->importLineRepository->add($line);

            return;
        }
    }
}
