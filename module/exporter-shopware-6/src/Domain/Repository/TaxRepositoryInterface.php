<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

interface TaxRepositoryInterface
{
    public function load(ChannelId $channelId, float $tax): ?string;

    public function save(ChannelId $channelId, float $tax, string $shopwareId): void;

    public function exists(ChannelId $channelId, float $tax): bool;
}
