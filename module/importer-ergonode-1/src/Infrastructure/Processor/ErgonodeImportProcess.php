<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Processor;

use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Infrastructure\Processor\SourceImportProcessorInterface;
use Ergonode\ImporterErgonode\Domain\Entity\ErgonodeZipSource;
use Ergonode\ImporterErgonode\Infrastructure\Reader\ErgonodeZipExtractor;
use Psr\Log\LoggerInterface;
use Throwable;

class ErgonodeImportProcess implements SourceImportProcessorInterface
{
    private ErgonodeZipExtractor $extractor;
    private LoggerInterface $logger;
    private array $steps;

    public function __construct(
        ErgonodeZipExtractor $extractor,
        LoggerInterface $logger,
        array $steps
    ) {
        $this->extractor = $extractor;
        $this->logger = $logger;
        $this->steps = $steps;
    }

    public function supported(string $type): bool
    {
        return $type === ErgonodeZipSource::TYPE;
    }

    public function start(Import $import): void
    {
        try {
            $zipDirectory = $this->extractor->extract($import);
            foreach ($this->steps as $step) {
                $step($import, $zipDirectory);
            }
        } catch (Throwable $exception) {
            $this->logger->critical($exception);
        } finally {
            $this->extractor->cleanup($import);
        }
    }
}
