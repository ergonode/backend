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

/**
 * Fixes file permission for jwt private and public key
 */
class JwtFixPermissions extends Command
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
     * @param string $secretKeyPath
     * @param string $publicKeyPath
     */
    public function __construct(string $secretKeyPath, string $publicKeyPath)
    {

        $this->secretKeyPath = $secretKeyPath;
        $this->publicKeyPath = $publicKeyPath;

        parent::__construct('ergonode:jwt:fix-permissions');
    }

    /**
     */
    public function configure()
    {
        $this
            ->setDescription(
                'Fixes file permission for jwt private and public key'
            )
        ->addOption(
            'private-key-group',
            'g',
            InputOption::VALUE_OPTIONAL,
            'Change jwt private key primary group'
        );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $group = $input->getOption('private-key-group');
        if ($group) {
            chgrp($this->secretKeyPath, $group);
        }

        chmod($this->secretKeyPath, 0640);
        chmod($this->publicKeyPath, 0644);

        (new SymfonyStyle($input, $output))->success('The permission successfully changed');
    }
}
