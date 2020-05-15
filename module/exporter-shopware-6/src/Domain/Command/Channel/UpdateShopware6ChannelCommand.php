<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Command\Channel;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;


/**
 */
class UpdateShopware6ChannelCommand implements DomainCommandInterface
{
    /**
     * @var ChannelId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ChannelId")
     */
    private ChannelId $channelId;

    /**
     * @var CategoryTreeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId")
     */
    private CategoryTreeId $categoryTreeId;

    /**
     * @param ChannelId      $channelId
     * @param CategoryTreeId $categoryTreeId
     */
    public function __construct(ChannelId $channelId, CategoryTreeId $categoryTreeId)
    {
        $this->channelId = $channelId;
        $this->categoryTreeId = $categoryTreeId;
    }

    /**
     * @return ChannelId
     */
    public function getChannelId(): ChannelId
    {
        return $this->channelId;
    }

    /**
     * @return CategoryTreeId
     */
    public function getCategoryTreeId(): CategoryTreeId
    {
        return $this->categoryTreeId;
    }
}
