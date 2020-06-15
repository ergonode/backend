<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Application\Command;

use Ergonode\Channel\Domain\Command\CreateChannelCommand;
use Ergonode\Channel\Domain\Command\StartChannelExportCommand;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\ExporterShopware6\Domain\Command\CreateShopware6ExportProfileCommand;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 */
class ExportOneProductCommand extends Command
{
    protected static $defaultName = 'test:export:shopware-product';

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param CommandBusInterface $commandBus
     */
    public function __construct(CommandBusInterface $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }


    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $exportProfileId = $this->createExportProfile();
        $channelId = $this->createChannel($exportProfileId);

        $this->start($channelId, $exportProfileId);
    }

    /**
     * @param ChannelId       $channelId
     * @param ExportProfileId $exportProfileId
     *
     * @throws \Exception
     */
    private function start(ChannelId $channelId, ExportProfileId $exportProfileId)
    {
        $command = new StartChannelExportCommand(
            ExportId::generate(),
            $channelId,
            $exportProfileId
        );

        $this->commandBus->dispatch($command);
    }

    /**
     * @param ExportProfileId $exportProfileId
     *
     * @return ChannelId
     *
     * @throws \Exception
     */
    private function createChannel(ExportProfileId $exportProfileId): ChannelId
    {
        $command = new CreateChannelCommand(
            'Test WF Cahnnel',
            $exportProfileId
        );

        $this->commandBus->dispatch($command);

        return $command->getId();
    }

    /**
     * @return ExportProfileId
     *
     * @throws \Exception
     */
    private function createExportProfile()
    {
        $command = new CreateShopware6ExportProfileCommand(
            ExportProfileId::generate(),
            'TEST WF',
            'http://192.168.55.98:8000',
            'SWIAMURTYTK0R2RQEFBVUNPDTQ',
            'Mml6ZkJoRVdGSlZhbDNwMjZEcDFRMUQ0a1JRNUJKWDFKMWNnV08',
            Language::fromString('en'),
            $this->getNameAttribute(),
            $this->getActive(),
            $this->getStock(),
            $this->getPrice(),
            $this->getTax(),
            []
        );

        $this->commandBus->dispatch($command);

        return $command->getId();
    }

    /**
     * @return AttributeId
     */
    private function getNameAttribute(): AttributeId
    {
        return AttributeId::fromKey('code_1');
    }

    /**
     * @return AttributeId
     */
    private function getActive(): AttributeId
    {
        return AttributeId::fromKey('code_2');
    }

    /**
     * @return AttributeId
     */
    private function getStock(): AttributeId
    {
        return AttributeId::fromKey('code_21');
    }

    /**
     * @return AttributeId
     */
    private function getPrice(): AttributeId
    {
        return AttributeId::fromKey('code_31');
    }

    /**
     * @return AttributeId
     */
    private function getTax(): AttributeId
    {
        return AttributeId::fromKey('code_22');
    }
}
