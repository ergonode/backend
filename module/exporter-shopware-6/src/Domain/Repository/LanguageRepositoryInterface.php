<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Domain\Repository;

use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

interface LanguageRepositoryInterface
{
    public function load(ChannelId $channelId, string $iso): ?Shopware6Language;

    public function save(ChannelId $channelId, Shopware6Language $shopware6Language): void;

    public function exists(ChannelId $channelId, string $iso): bool;
}
