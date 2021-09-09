<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration\Command;

use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\Migrations\Version\Version;
use Ergonode\Migration\Provider\MigrationConfigurationProvider;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MigrateMigrationCommand extends MigrateCommand
{
    public function __construct(MigrationConfigurationProvider $configurationService)
    {
        $this->setMigrationConfiguration($configurationService->configure());
        parent::__construct();
    }
    public function initialize(InputInterface $input, OutputInterface $output): void
    {
        /** @var Application $application */
        $application = $this->getApplication();
        $configuration = $this->getMigrationConfiguration($input, $output);
        $container     = $application->getKernel()->getContainer();
        $this->injectContainerToMigrations($container, $configuration->getMigrations());

        parent::initialize($input, $output);
    }

    protected function configure(): void
    {
        parent::configure();

        $this->setName('ergonode:migrations:migrate');
    }

    /**
     * @param Version[] $versions
     */
    private function injectContainerToMigrations(ContainerInterface $container, array $versions): void
    {
        foreach ($versions as $version) {
            $migration = $version->getMigration();
            if (!($migration instanceof ContainerAwareInterface)) {
                continue;
            }

            $migration->setContainer($container);
        }
    }
}
