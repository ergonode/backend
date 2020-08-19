<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Repository;

use Ergonode\ExporterShopware6\Domain\Entity\Catalog\Shopware6Category;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
interface Shopware6CategoryRepositoryInterface
{
    /**
     * @param ChannelId  $channelId
     * @param CategoryId $categoryId
     *
     * @return Shopware6Category|null
     */
    public function load(ChannelId $channelId, CategoryId $categoryId): ?Shopware6Category;

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
}
