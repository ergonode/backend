<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ergonode\Reader\Infrastructure\Processor\CsvReaderProcessor;
use Ergonode\Reader\Domain\Formatter\EncodingFormatter;
use Ergonode\Reader\Domain\Formatter\ReplaceFormatter;
use Ergonode\ImporterMagento2\Infrastructure\Builder\ImportConfigurationBuilder;

/**
 */
class DomainEntityCreatorCommand extends Command
{
    /**
     * @var CsvReaderProcessor
     */
    private CsvReaderProcessor $processor;

    /**
     * @var ImportConfigurationBuilder
     */
    private ImportConfigurationBuilder $builder;

    /**
     * @param CsvReaderProcessor         $processor
     * @param ImportConfigurationBuilder $builder
     */
    public function __construct(
        CsvReaderProcessor $processor,
        ImportConfigurationBuilder $builder
    ) {
        $this->processor = $processor;
        $this->builder = $builder;

        parent::__construct();
    }

    /**
     */
    public function configure(): void
    {
        $this->setName('ergonode:test:csv');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->processor->open(
            'import.csv',
            [],
            [
                new EncodingFormatter('WINDOWS-1250'),
                new ReplaceFormatter('/\\\\t$/', ''),
                new ReplaceFormatter('/\^ON$/', ''),
                new ReplaceFormatter('/^\^/', ''),
            ]
        );

        var_dump($this->processor->getHeaders());

        var_dump('START');

        $headers = $this->processor->getHeaders();
        $lines = [];

        $i=0;
        foreach ($this->processor->read() as $line) {
            $i++;
            if($i > 100) break;
            $lines[] = $line;
        }

        $result = $this->builder->propose($headers, $lines);

       // echo print_r($result, true);

        var_dump('END');
    }
}
