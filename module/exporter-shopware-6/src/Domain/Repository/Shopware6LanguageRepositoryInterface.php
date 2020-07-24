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
interface Shopware6LanguageRepositoryInterface
{
    /**
     * @param ChannelId $channelId
     * @param string    $name
     *
     * @return string|null
     */
    public function load(ChannelId $channelId, string $name): ?string;

    /**
     * @param ChannelId $channelId
     * @param string    $name
     * @param string    $shopwareId
     */
    public function save(ChannelId $channelId, string $name, string $shopwareId): void;

    /**
     * @param ChannelId $channelId
     * @param string    $name
     *
     * @return bool
     */
    public function exists(ChannelId $channelId, string $name): bool;
}
