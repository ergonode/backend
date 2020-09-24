<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Repository;

use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
interface Shopware6LanguageRepositoryInterface
{
    /**
     * @param ChannelId $channelId
     * @param string    $iso
     *
     * @return Shopware6Language|null
     */
    public function load(ChannelId $channelId, string $iso): ?Shopware6Language;

    /**
     * @param ChannelId         $channelId
     * @param Shopware6Language $shopware6Language
     */
    public function save(ChannelId $channelId, Shopware6Language $shopware6Language): void;

    /**
     * @param ChannelId $channelId
     * @param string    $iso
     *
     * @return bool
     */
    public function exists(ChannelId $channelId, string $iso): bool;
}
