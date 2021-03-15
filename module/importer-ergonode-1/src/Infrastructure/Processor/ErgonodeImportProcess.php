<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Processor;

use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\Importer\Infrastructure\Processor\SourceImportProcessorInterface;
use Ergonode\ImporterErgonode1\Domain\Entity\ErgonodeZipSource;
use Ergonode\ImporterErgonode1\Infrastructure\Reader\ErgonodeZipExtractor;
use Ergonode\ImporterErgonode1\Infrastructure\Reader\Exception\ErgonodeZipExtractorException;
use Ergonode\ImporterErgonode1\Infrastructure\Reader\Exception\ReaderFileProcessException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Webmozart\Assert\Assert;

class ErgonodeImportProcess implements SourceImportProcessorInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    private ErgonodeZipExtractor $extractor;
    private SourceRepositoryInterface $repository;
    /**
     * @var ErgonodeProcessorStepInterface[]
     */
    private array $steps;

    public function __construct(
        ErgonodeZipExtractor $extractor,
        array $steps,
        SourceRepositoryInterface $repository
    ) {
        Assert::allIsInstanceOf($steps, ErgonodeProcessorStepInterface::class);
        $this->extractor = $extractor;
        $this->steps = $steps;
        $this->repository = $repository;
    }

    public function supported(string $type): bool
    {
        return $type === ErgonodeZipSource::TYPE;
    }

    /**
     * @throws \Ergonode\ImporterErgonode1\Infrastructure\Reader\Exception\ErgonodeZipExtractorException
     */
    public function start(Import $import): void
    {
        try {
            $zipDirectory = $this->extractor->extract($import);
        } catch (ErgonodeZipExtractorException $exception) {
            $this->logger->warning($exception);
            throw new ImportException('Invalid zip source');
        }

        try {
            /** @var ErgonodeZipSource $source */
            $source = $this->repository->load($import->getSourceId());
            Assert::isInstanceOf($source, ErgonodeZipSource::class);

            foreach ($this->steps as $step) {
                $step($import, $source, $zipDirectory);
            }
        } catch (ReaderFileProcessException $exception) {
            $this->logger->warning($exception);
            throw new ImportException(sprintf('Can\'t process file %s', $exception->getFilename()));
        } finally {
            $this->extractor->cleanup($import);
        }
    }
}
