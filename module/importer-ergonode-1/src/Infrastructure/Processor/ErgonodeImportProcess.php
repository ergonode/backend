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
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Importer\Infrastructure\Processor\SourceImportProcessorInterface;
use Ergonode\ImporterErgonode\Domain\Entity\ErgonodeZipSource;
use Ergonode\ImporterErgonode\Infrastructure\Reader\ErgonodeZipExtractor;
use Ergonode\Reader\Infrastructure\Exception\ReaderException;
use Throwable;

/**
 */
final class ErgonodeImportProcess implements SourceImportProcessorInterface
{
    /**
     * @var ImportErrorRepositoryInterface
     */
    private ImportErrorRepositoryInterface $importErrorRepository;

    /**
     * @var ErgonodeZipExtractor
     */
    private ErgonodeZipExtractor $extractor;

    /**
     * @var ErgonodeProcessorStepInterface[]
     */
    private array $steps;

    /**
     * @param ImportErrorRepositoryInterface   $importErrorRepository
     * @param ErgonodeZipExtractor             $extractor
     * @param ErgonodeProcessorStepInterface[] $steps
     */
    public function __construct(
        ImportErrorRepositoryInterface $importErrorRepository,
        ErgonodeZipExtractor $extractor,
        array $steps
    ) {
        $this->importErrorRepository = $importErrorRepository;
        $this->extractor = $extractor;
        $this->steps = $steps;
    }

    /**
     * {@inheritDoc}
     */
    public function supported(string $type): bool
    {
        return $type === ErgonodeZipSource::TYPE;
    }

    /**
     * {@inheritDoc}
     */
    public function start(Import $import): void
    {
        try {
            $zipDirectory = $this->extractor->extract($import);
            foreach ($this->steps as $step) {
                $step($import, $zipDirectory);
            }
        } catch (ImportException|ReaderException $exception) {
            $this->notifyError($import, $exception->getMessage());
        } catch (Throwable $exception) {
            $this->notifyError($import, 'Import processing error');
        } finally {
            $this->extractor->cleanup($import);
        }
    }

    /**
     * @param Import $import
     * @param string $message
     */
    private function notifyError(Import $import, string $message): void
    {
        $error = new ImportError($import->getId(), $message);
        $import->stop();
        $this->importErrorRepository->add($error);
    }
}
