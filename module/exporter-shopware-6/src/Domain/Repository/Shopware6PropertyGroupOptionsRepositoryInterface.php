<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
interface Shopware6PropertyGroupOptionsRepositoryInterface
{
    /**
     * @param ChannelId   $channelId
     * @param AttributeId $attributeId
     * @param string      $value
     *
     * @return string|null
     */
    public function load(ChannelId $channelId, AttributeId $attributeId, string $value): ?string;

    /**
     * @param ChannelId   $channelId
     * @param AttributeId $attributeId
     * @param string      $value
     * @param string      $shopwareId
     */
    public function save(ChannelId $channelId, AttributeId $attributeId, string $value, string $shopwareId): void;

    /**
     * @param ChannelId   $channelId
     * @param AttributeId $attributeId
     * @param string      $value
     *
     * @return bool
     */
    public function exists(ChannelId $channelId, AttributeId $attributeId, string $value): bool;
}
