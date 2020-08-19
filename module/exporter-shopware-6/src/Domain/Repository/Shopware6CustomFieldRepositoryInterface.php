<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
interface Shopware6CustomFieldRepositoryInterface
{
    /**
     * @param ChannelId   $channel
     * @param AttributeId $attributeId
     *
     * @return string|null
     */
    public function load(ChannelId $channel, AttributeId $attributeId): ?string;

    /**
     * @param ChannelId   $channel
     * @param AttributeId $attributeId
     * @param string      $shopwareId
     * @param string      $type
     */
    public function save(ChannelId $channel, AttributeId $attributeId, string $shopwareId, string $type): void;

    /**
     * @param ChannelId   $channel
     * @param AttributeId $attributeId
     *
     * @return bool
     */
    public function exists(ChannelId $channel, AttributeId $attributeId): bool;
}
