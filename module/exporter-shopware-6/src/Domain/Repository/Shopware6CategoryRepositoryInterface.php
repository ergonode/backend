<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

interface Shopware6CategoryRepositoryInterface
{
    /**
     * @param ChannelId  $channelId
     * @param CategoryId $categoryId
     *
     * @return string|null
     */
    public function load(ChannelId $channelId, CategoryId $categoryId): ?string;

    /**
     * @param ChannelId  $channelId
     * @param CategoryId $categoryId
     * @param string     $shopwareId
     */
    public function save(ChannelId $channelId, CategoryId $categoryId, string $shopwareId): void;

    /**
     * @param ChannelId  $channelId
     * @param CategoryId $categoryId
     *
     * @return bool
     */
    public function exists(ChannelId $channelId, CategoryId $categoryId): bool;

    /**
     * @param ChannelId  $channelId
     * @param CategoryId $categoryId
     */
    public function delete(ChannelId $channelId, CategoryId $categoryId): void;
}
