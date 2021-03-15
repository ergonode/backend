<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration\Command;

use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Tools\Console\Command\AbstractCommand;
use Ergonode\Migration\Provider\MigrationConfigurationProvider;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateMigrationCommand extends AbstractCommand
{
    private const FILENAME_TEMPLATE = '%s/Version%s.php';

    public function __construct(MigrationConfigurationProvider $configurationService)
    {
        $this->setMigrationConfiguration($configurationService->configure());

        parent::__construct();
    }

    /**
     * @return null|int|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $configuration = $this->getMigrationConfiguration($input, $output);

        $template = \file_get_contents(__DIR__.'/../Resources/migration.tpl');

        $version = $configuration->generateVersionNumber();
        $path = $this->generateMigration($configuration, $version, $template);

        $output->writeln(\sprintf('Generated migration class:"<info>%s</info>"', $path));
    }

    protected function generateMigration(Configuration $configuration, string $version, string $template): string
    {
        $migration = $this->replace($template, $version, $configuration->getMigrationsNamespace());

        $dir = $configuration->getMigrationsDirectory();
        $path = \sprintf(self::FILENAME_TEMPLATE, $dir, $version);

        \file_put_contents($path, $migration);

        return $path;
    }

    protected function configure(): void
    {
        parent::configure();

        $this->setName('ergonode:migrations:generate');
    }

    private function replace(string $template, string $version, string $namespace): string
    {
        $placeholders = $this->getPlaceholders($version, $namespace);

        return \str_replace(
            \array_keys($placeholders),
            \array_values($placeholders),
            $template
        );
    }

    /**
     * @return array
     */
    private function getPlaceholders(string $version, string $namespace): array
    {
        return [
            '%namespace%' => $namespace,
            '%version%' => $version,
        ];
    }
}
