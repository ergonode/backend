<?php

/**
 * Copyright © Ergonaut Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Infrastructure\Service\Decorator;

use Ergonode\Channel\Domain\Entity\Channel;
use Ergonode\Channel\Domain\Service\ChannelExportServiceInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Psr\Log\LoggerInterface;

/**
 */
class LoggerChannelExportServiceDecorator implements ChannelExportServiceInterface
{
    /**
     * @var ChannelExportServiceInterface
     */
    private $service;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ChannelExportServiceInterface $service
     * @param LoggerInterface               $logger
     */
    public function __construct(ChannelExportServiceInterface $service, LoggerInterface $logger)
    {
        $this->service = $service;
        $this->logger = $logger;
    }

    /**
     * @param Channel         $channel
     * @param AbstractProduct $product
     */
    public function process(Channel $channel, AbstractProduct $product): void
    {
        $this->logger->info(sprintf(
            'Exporting %s from channel %s',
            $product->getSku(),
            $channel->getName()
        ));

        $this->service->process($channel, $product);
    }
}
