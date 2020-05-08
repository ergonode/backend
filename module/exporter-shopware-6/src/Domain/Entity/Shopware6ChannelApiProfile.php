<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Entity;

use Ergonode\Exporter\Domain\Entity\Configuration\AbstractChannelConfiguration;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class Shopware6ChannelApiProfile extends AbstractChannelConfiguration
{
    public const TYPE = 'shopware-6-api-config';

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
        parent::__construct($channelId);
        $this->categoryTreeId = $categoryTreeId;
    }

    /**
     * @return CategoryTreeId
     */
    public function getCategoryTreeId(): CategoryTreeId
    {
        return $this->categoryTreeId;
    }
}
