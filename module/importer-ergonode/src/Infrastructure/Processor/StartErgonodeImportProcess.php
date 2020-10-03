<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterErgonode\Infrastructure\Processor;

use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Entity\ImportError;
use Ergonode\Importer\Domain\Repository\ImportErrorRepositoryInterface;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Importer\Infrastructure\Processor\SourceImportProcessorInterface;
use Ergonode\ImporterErgonode\Domain\Entity\ErgonodeCsvSource;
use Ergonode\ImporterErgonode\Infrastructure\Reader\ErgonodeCsvReader;
use Ergonode\Reader\Infrastructure\Exception\ReaderException;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class StartErgonodeImportProcess implements SourceImportProcessorInterface
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
     * @var ErgonodeCsvReader
     */
    private ErgonodeCsvReader $reader;

    /**
     * @var ErgonodeProcessorStepInterface[]
     */
    private array $steps;

    /**
     * @param SourceRepositoryInterface        $sourceRepository
     * @param TransformerRepositoryInterface   $transformerRepository
     * @param ImportErrorRepositoryInterface   $importErrorRepository
     * @param ErgonodeCsvReader                $reader
     * @param ErgonodeProcessorStepInterface[] $steps
     */
    public function __construct(
        SourceRepositoryInterface $sourceRepository,
        TransformerRepositoryInterface $transformerRepository,
        ImportErrorRepositoryInterface $importErrorRepository,
        ErgonodeCsvReader $reader,
        array $steps
    ) {
        $this->sourceRepository = $sourceRepository;
        $this->transformerRepository = $transformerRepository;
        $this->importErrorRepository = $importErrorRepository;
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
        return $type === ErgonodeCsvSource::TYPE;
    }

    /**
     * @param Import $import
     *
     * @throws \ReflectionException
     */
    public function start(Import $import): void
    {
        /** @var ErgonodeCsvSource $source */
        $source = $this->sourceRepository->load($import->getSourceId());
        Assert::notNull($source);
        $transformer = $this->transformerRepository->load($import->getTransformerId());
        Assert::notNull($transformer);

        $message = null;

        try {
            $this->reader->open($import);
            while ($product = $this->reader->read($transformer)) {
                foreach ($this->steps as $step) {
                    $step->process($import, $product, $transformer, $source);
                }
            }
        } catch (ImportException|ReaderException $exception) {
            $message = $exception->getMessage();
        } catch (\Throwable $exception) {
            $message = 'Import processing error';
        }

        if ($message) {
            $error = new ImportError($import->getId(), $message);
            $import->stop();
            $this->importErrorRepository->add($error);
        }
    }
}
