<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

interface Shopware6CurrencyRepositoryInterface
{
    public function load(ChannelId $channel, string $iso): ?string;

    public function save(ChannelId $channel, string $iso, string $shopwareId): void;

    public function exists(ChannelId $channel, string $iso): bool;
}
