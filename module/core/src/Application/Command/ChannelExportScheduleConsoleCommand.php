<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ergonode\Channel\Domain\Command\Schedule\ScheduleCommand;
use Ergonode\Core\Application\Installer\InstallerProvider;

/**
 */
class ChannelExportScheduleConsoleCommand extends Command
{
    private const NAME = 'ergonode:install';

    /**
     * @var InstallerProvider
     */
    private InstallerProvider $provider;

    /**
     * @param InstallerProvider $provider
     */
    public function __construct(InstallerProvider $provider)
    {
        parent::__construct(static::NAME);

        $this->provider = $provider;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->provider->get() as $installer) {
            $installer->install();
        }

        return 0;
    }
}
