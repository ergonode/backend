<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration\Provider;

use Doctrine\DBAL\Connection;
use Doctrine\Migrations\Configuration\Configuration;

class MigrationConfigurationProvider
{
    private const NAMESPACE = 'Ergonode\\Migration';
    private const TABLE_NAME = 'migrations';

    private Connection $connection;

    private MigrationDirectoryProviderInterface $migrationDirectoryProvider;

    public function __construct(
        Connection $connection,
        MigrationDirectoryProviderInterface $migrationDirectoryProvider
    ) {
        $this->connection = $connection;
        $this->migrationDirectoryProvider = $migrationDirectoryProvider;
    }

    public function configure(): Configuration
    {
        $configuration = new Configuration($this->connection);
        $configuration->setMigrationsTableName(self::TABLE_NAME);
        $configuration->setMigrationsDirectory($this->migrationDirectoryProvider->getMainDirectory());
        $configuration->setMigrationsNamespace(self::NAMESPACE);

        foreach ($this->migrationDirectoryProvider->getDirectoryCollection() as $directory) {
            $configuration->registerMigrationsFromDirectory($directory);
        }

        return $configuration;
    }
}
