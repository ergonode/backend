<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Infrastructure\Service;

use Ergonode\Channel\Domain\Entity\Channel;
use Ergonode\Channel\Domain\Provider\ChannelCollectionProviderInterface;
use Ergonode\Channel\Domain\Service\ChannelServiceInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;

/**
 */
class ChannelService implements ChannelServiceInterface
{
    /**
     * @var ChannelCollectionProviderInterface
     */
    private $channelCollectionProvider;

    /**
     * @var ChannelValidationService
     */
    private $validationService;

    /**
     * @var ChannelExportService
     */
    private $exportService;

    /**
     * @param ChannelCollectionProviderInterface $channelCollectionProvider
     * @param ChannelValidationService           $validationService
     * @param ChannelExportService               $exportService
     */
    public function __construct(
        ChannelCollectionProviderInterface $channelCollectionProvider,
        ChannelValidationService $validationService,
        ChannelExportService $exportService
    ) {
        $this->channelCollectionProvider = $channelCollectionProvider;
        $this->validationService = $validationService;
        $this->exportService = $exportService;
    }

    /**
     * @param AbstractProduct $product
     */
    public function process(AbstractProduct $product): void
    {
        $channels = $this->channelCollectionProvider->provide();

        /** @var Channel $channel */
        foreach ($channels as $channel) {
            if ($this->validationService->isValid($product, $channel)) {
                $this->exportService->process($channel, $product);
            }
        }
    }
}
