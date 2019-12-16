<?php

/**
 * Copyright © Ergonaut Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Infrastructure\Service;

use Ergonode\Channel\Domain\Command\ExportProductCommand;
use Ergonode\Channel\Domain\Entity\Channel;
use Ergonode\Channel\Domain\Service\ChannelExportServiceInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 */
class ChannelExportService implements ChannelExportServiceInterface
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @param Channel         $channel
     * @param AbstractProduct $product
     */
    public function process(Channel $channel, AbstractProduct $product): void
    {
        $command = new ExportProductCommand(
            $channel->getId(),
            $product->getId()
        );

        $this->messageBus->dispatch($command);
    }
}
