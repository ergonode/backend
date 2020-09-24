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
interface Shopware6CurrencyRepositoryInterface
{
    /**
     * @param ChannelId $channel
     * @param string    $iso
     *
     * @return string|null
     */
    public function load(ChannelId $channel, string $iso): ?string;

    /**
     * @param ChannelId $channel
     * @param string    $iso
     * @param string    $shopwareId
     */
    public function save(ChannelId $channel, string $iso, string $shopwareId): void;

    /**
     * @param ChannelId $channel
     * @param string    $iso
     *
     * @return bool
     */
    public function exists(ChannelId $channel, string $iso): bool;
}
