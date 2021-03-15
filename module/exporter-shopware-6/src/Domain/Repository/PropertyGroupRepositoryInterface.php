<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

interface PropertyGroupRepositoryInterface
{
    public function load(ChannelId $channelId, AttributeId $attributeId): ?string;

    public function save(ChannelId $channelId, AttributeId $attributeId, string $shopwareId, string $type): void;

    public function exists(ChannelId $channelId, AttributeId $attributeId): bool;
}
