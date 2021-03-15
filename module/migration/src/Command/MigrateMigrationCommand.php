<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration\Command;

use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use Ergonode\Migration\Provider\MigrationConfigurationProvider;

class MigrateMigrationCommand extends MigrateCommand
{
    public function __construct(MigrationConfigurationProvider $configurationService)
    {
        $this->setMigrationConfiguration($configurationService->configure());
        parent::__construct();
    }

    protected function configure(): void
    {
        parent::configure();

        $this->setName('ergonode:migrations:migrate');
    }
}
