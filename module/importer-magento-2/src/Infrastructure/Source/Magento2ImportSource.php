<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento2\Infrastructure\Source;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\Importer\Infrastructure\Builder\ImportConfigurationBuilder;
use Ergonode\Importer\Infrastructure\Configuration\ImportConfiguration;
use Ergonode\Importer\Infrastructure\Provider\ImportSourceInterface;
use Ergonode\ImporterMagento2\Domain\Entity\Magento2CsvSource;
use Ergonode\Reader\Infrastructure\Processor\CsvReaderProcessor;

/**
 */
class Magento2ImportSource implements ImportSourceInterface
{
    public const TYPE = 'magento-2-csv';

    /**
     * @var CsvReaderProcessor
     */
    private CsvReaderProcessor $processor;

    /**
     * @var ImportConfigurationBuilder
     */
    private ImportConfigurationBuilder $builder;

    /**
     * @var string
     */
    private string $directory;

    /**
     * @param CsvReaderProcessor         $processor
     * @param ImportConfigurationBuilder $builder
     * @param string                     $directory
     */
    public function __construct(CsvReaderProcessor $processor, ImportConfigurationBuilder $builder, string $directory)
    {
        $this->processor = $processor;
        $this->builder = $builder;
        $this->directory = $directory;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return self::TYPE === $type;
    }

    /**
     * @param AbstractSource|Magento2CsvSource $source
     *
     * @return ImportConfiguration
     *
     * @throws \Exception
     */
    public function process(AbstractSource $source): ImportConfiguration
    {
        $file = sprintf('%s%s', $this->directory, $source->getFile());

        $this->processor->open($file, $source->getConfiguration());

        $headers = $this->processor->getHeaders();
        $lines = [];

        $i = 0;
        foreach ($this->processor->read() as $line) {
            $i++;
            if ($i > 100) {
                break;
            }

            $lines[] = $line;
        }

        return $this->builder->propose($headers, $lines);
    }
}
