<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration\Command;

use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use Ergonode\Migration\Provider\MigrationConfigurationProvider;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Migrations\Version\Version;

/**
 */
class MigrateMigrationCommand extends MigrateCommand
{
     /**
     * @param MigrationConfigurationProvider $configurationService
     */
    public function __construct(MigrationConfigurationProvider $configurationService)
    {
        $this->setMigrationConfiguration($configurationService->configure());
        parent::__construct();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function initialize(InputInterface $input, OutputInterface $output) : void
    {
        $application = $this->getApplication();
        $configuration = $this->getMigrationConfiguration($input, $output);
        $container     = $application->getKernel()->getContainer();
        assert($container instanceof ContainerInterface);

        self::injectContainerToMigrations($container, $configuration->getMigrations());

        parent::initialize($input, $output);
    }


    /**
     */
    protected function configure(): void
    {
        parent::configure();

        $this->setName('ergonode:migrations:migrate');
    }

    /**
     * @param ContainerInterface $container
     * @param Version[]          $versions
     *
     * Injects the container to migrations aware of it
     */
    private static function injectContainerToMigrations(ContainerInterface $container, array $versions) : void
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
