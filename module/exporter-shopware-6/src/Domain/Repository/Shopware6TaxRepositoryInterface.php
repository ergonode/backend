<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
interface Shopware6TaxRepositoryInterface
{
    /**
     * @param ChannelId $channelId
     * @param float     $tax
     *
     * @return string|null
     */
    public function load(ChannelId $channelId, float $tax): ?string;

    /**
     * @param ChannelId $channelId
     * @param float     $tax
     * @param string    $shopwareId
     */
    public function save(ChannelId $channelId, float $tax, string $shopwareId): void;

    /**
     * @param ChannelId $channelId
     * @param float     $tax
     *
     * @return bool
     */
    public function exists(ChannelId $channelId, float $tax): bool;
}
