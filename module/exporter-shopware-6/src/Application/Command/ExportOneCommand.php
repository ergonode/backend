<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Application\Command;

use Ergonode\Channel\Domain\Command\ExportChannelCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportOneCommand extends Command
{
    /**
     * @var mixed
     */
    protected static $defaultName = 'test:export:shopware-product';

    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $channelId = new ChannelId('4ca1a604-3a66-42fa-b93f-d7e09773ae64');

        $this->start($channelId);
    }

    /**
     * @throws \Exception
     */
    private function start(ChannelId $channelId): void
    {
        $command = new ExportChannelCommand(
            ExportId::generate(),
            $channelId
        );

        $this->commandBus->dispatch($command);
    }
}
