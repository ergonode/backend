<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Application\Command;

use Ergonode\ImporterErgonode\Infrastructure\Reader\ErgonodeCategoryReader;
use League\Csv\Writer;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Debug command for Ergonode category reader
 */
final class ImporterCategoryFileGeneratorCommand extends Command
{
    /**
     * @var string
     */
    private string $directory;

    /**
     * @var Stopwatch
     */
    private Stopwatch $stopwatch;

    /**
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        parent::__construct(null);
        $this->directory = "{$kernel->getProjectDir()}/var/tmp/";
        $this->stopwatch = new Stopwatch();
    }

    /**
     */
    public function configure(): void
    {
        $this->setName('ergonode:importer:category:debug');
        $this->setDescription('Generate Ergonode category import file');
        $this->addOption('element_count', 'c', InputOption::VALUE_OPTIONAL, 'Element count', 1000);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Ergonode\ImporterErgonode\Infrastructure\Reader\Exception\ReaderFileProcessException
     * @throws \League\Csv\CannotInsertRecord
     * @throws \League\Csv\Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $elementCount = (int) $input->getOption('element_count');
        $file = $this->generateFile($elementCount);
        $output->writeln("File saved at \"$file\"");
        $this->process($elementCount, $file, $output);
        $output->writeln('Done');
    }

    /**
     * @param int $count
     *
     * @return string
     *
     * @throws \League\Csv\CannotInsertRecord
     * @throws \League\Csv\Exception
     */
    private function generateFile(int $count): string
    {
        $file = "categories_{$count}.csv";
        $filepath = "{$this->directory}{$file}";

        if (file_exists($filepath)) {
            return $file;
        }

        $writer = Writer::createFromPath($filepath, 'w+');
        $writer->setDelimiter(',');
        $writer->setEscape('"');
        $writer->setEnclosure('"');
        $writer->insertOne(['_id', '_code', '_name', '_language']);

        $codeId = 1;
        $id = Uuid::uuid4()->toString();
        for ($i = 0; $i < $count; $i++) {
            $code = "code_{$codeId}";
            $language = $i % 2 === 0 ? 'pl_PL' : 'en_GB';
            $name = "Name {$codeId} {$language}";
            $writer->insertOne([$id, $code, $name, $language]);

            if (1 === $i % 2) {
                $codeId++;
                $id = Uuid::uuid4()->toString();
            }
        }

        return $file;
    }

    /**
     * @param int             $count
     * @param string          $file
     * @param OutputInterface $output
     *
     * @throws \Ergonode\ImporterErgonode\Infrastructure\Reader\Exception\ReaderFileProcessException
     */
    private function process(int $count, string $file, OutputInterface $output): void
    {
        $reader = new ErgonodeCategoryReader($this->directory, $file);
        $c = 0;
        $p = $count / 10;

        $this->stopwatch->start('process');

        while ($category = $reader->read()) {
            $c++;
            if (0 === $c % $p) {
                $e = $this->stopwatch->stop('process');
                $duration = $e->getDuration()/1000;
                $memory = $e->getMemory()/1024;
                $output->writeln("Processed {$c}: {$duration}s / {$memory}kB");
                $this->stopwatch->start('process');
            }
        }

        $this->stopwatch->stop('process');
    }
}
