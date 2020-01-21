<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Command;

use Ergonode\Channel\Domain\Entity\ChannelId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Product\Domain\Entity\ProductId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ExportProductChannelCommand implements DomainCommandInterface
{
    /**
     * @var ChannelId
     *
     * @JMS\Type("Ergonode\Channel\Domain\Entity\ChannelId")
     */
    private ChannelId $channelId;

    /**
     * @var ProductId
     *
     * @JMS\Type("Ergonode\Product\Domain\Entity\ProductId")
     */
    private ProductId $productId;

    /**
     * @param ChannelId $channelId
     * @param ProductId $productId
     */
    public function __construct(ChannelId $channelId, ProductId $productId)
    {
        $this->channelId = $channelId;
        $this->productId = $productId;
    }

    /**
     * @return ChannelId
     */
    public function getChannelId(): ChannelId
    {
        return $this->channelId;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }
}
