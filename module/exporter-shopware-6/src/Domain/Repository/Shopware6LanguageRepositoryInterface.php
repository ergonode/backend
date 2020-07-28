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
     * @param string    $shopwareId
     *
     * @return string|null
     */
    public function load(ChannelId $channelId, string $shopwareId): ?string;

    /**
     * @param ChannelId $channelId
     * @param string    $shopwareId
     * @param string    $name
     * @param string    $localeId
     */
    public function save(ChannelId $channelId, string $shopwareId, string $name, string $localeId): void;

    /**
     * @param ChannelId $channelId
     * @param string    $shopwareId
     *
     * @return bool
     */
    public function exists(ChannelId $channelId, string $shopwareId): bool;
}
