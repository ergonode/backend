<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

interface CategoryRepositoryInterface
{
    public function load(ChannelId $channelId, CategoryId $categoryId): ?string;

    public function save(ChannelId $channelId, CategoryId $categoryId, string $shopwareId): void;

    public function exists(ChannelId $channelId, CategoryId $categoryId): bool;

    public function delete(ChannelId $channelId, CategoryId $categoryId): void;
}
