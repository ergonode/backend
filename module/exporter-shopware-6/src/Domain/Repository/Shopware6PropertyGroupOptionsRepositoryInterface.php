<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\AggregateId;

interface Shopware6PropertyGroupOptionsRepositoryInterface
{
    /**
     * @param ChannelId   $channelId
     * @param AttributeId $attributeId
     * @param AggregateId $optionId
     *
     * @return string|null
     */
    public function load(ChannelId $channelId, AttributeId $attributeId, AggregateId $optionId): ?string;

    /**
     * @param ChannelId   $channelId
     * @param AttributeId $attributeId
     * @param AggregateId $optionId
     * @param string      $shopwareId
     */
    public function save(
        ChannelId $channelId,
        AttributeId $attributeId,
        AggregateId $optionId,
        string $shopwareId
    ): void;

    /**
     * @param ChannelId   $channelId
     * @param AttributeId $attributeId
     * @param AggregateId $optionId
     *
     * @return bool
     */
    public function exists(ChannelId $channelId, AttributeId $attributeId, AggregateId $optionId): bool;
}
