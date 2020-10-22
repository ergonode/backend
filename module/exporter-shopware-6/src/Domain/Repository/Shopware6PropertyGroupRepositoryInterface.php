<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

interface Shopware6PropertyGroupRepositoryInterface
{
    /**
     * @param ChannelId   $channelId
     * @param AttributeId $attributeId
     *
     * @return string|null
     */
    public function load(ChannelId $channelId, AttributeId $attributeId): ?string;

    /**
     * @param ChannelId   $channelId
     * @param AttributeId $attributeId
     * @param string      $shopwareId
     * @param string      $type
     */
    public function save(ChannelId $channelId, AttributeId $attributeId, string $shopwareId, string $type): void;

    /**
     * @param ChannelId   $channelId
     * @param AttributeId $attributeId
     *
     * @return bool
     */
    public function exists(ChannelId $channelId, AttributeId $attributeId): bool;
}
