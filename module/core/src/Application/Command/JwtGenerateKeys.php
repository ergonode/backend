<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\InputStream;
use Symfony\Component\Process\Process;

class JwtGenerateKeys extends Command
{
    /**
     * @var string
     */
    private $secretKeyPath;

    /**
     * @var string
     */
    private $publicKeyPath;

    /**
     * @var string
     */
    private $secretKeyPassword;

    /**
     * @param string $secretKeyPath
     * @param string $publicKeyPath
     * @param string $secretKeyPassword
     */
    public function __construct(string $secretKeyPath, string $publicKeyPath, string $secretKeyPassword)
    {

        $this->secretKeyPath = $secretKeyPath;
        $this->publicKeyPath = $publicKeyPath;
        $this->secretKeyPassword = $secretKeyPassword;

        parent::__construct('ergonode:jwt:generate-keys');
    }

    public function configure()
    {
        $this
            ->setDescription(
                'Generates jwt private and public key in accordance with the app configuration'
        )->addOption(
            'overwrite',
            'o',
            InputOption::VALUE_OPTIONAL,
            'Should overwrite existing keys?',
            false
        );
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        if (!$this->canGenerateKeys($input))
        {
            $output->writeln(
                '<comment>Keys already exists for overwriting please use --overwrite option</comment>'
            );
            return;

        }

        $this->generateJwtPrivateKey($output);
        $this->generateJwtPublicKey($output);

        (new SymfonyStyle($input, $output))->success('The keys successfully generated');
    }

    /**
     * @param InputInterface $input
     * @return bool
     */
    private function canGenerateKeys(InputInterface $input): bool
    {
        $optionValue = $input->getOption('overwrite');
        $overwrite = ($optionValue !== false);

        if ($overwrite) {
            return true;
        }

        return !file_exists($this->secretKeyPath) && !file_exists($this->publicKeyPath);
    }

    /**
     * @param OutputInterface $output
     */
    private function generateJwtPrivateKey(OutputInterface $output)
    {
        $this->executeCommand(
            [
                'openssl',
                'genrsa',
                '-aes256',
                '-passout',
                'stdin',
                '-out',
                $this->secretKeyPath,
                4096
            ],
            $this->secretKeyPassword,
            $output
        );
    }

    /**
     * @param OutputInterface $output
     */
    private function generateJwtPublicKey(OutputInterface $output)
    {
        $this->executeCommand(
            [
                'openssl',
                'rsa',
                '-pubout',
                '-in',
                $this->secretKeyPath,
                '-passin',
                'stdin',
                '-out',
                $this->publicKeyPath,
            ],
            $this->secretKeyPassword,
            $output
        );
    }

    /**
     * @param array $command
     * @param string $secretKeyPassword
     * @param OutputInterface $output
     */
    private function executeCommand(array $command, string $secretKeyPassword, OutputInterface $output): void
    {
        $input = new InputStream();
        $process = new Process($command);
        $process->setInput($input);
        $process->setPty(false);
        $process->start();
        $input->write($secretKeyPassword);
        $input->close();
        $process->waitUntil(function ($type ,$cmdOutput) use ($output) {
            $output->write($cmdOutput);
        });

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}
