<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\AggregateId;

interface PropertyGroupOptionsRepositoryInterface
{
    public function load(ChannelId $channelId, AttributeId $attributeId, AggregateId $optionId): ?string;

    public function save(
        ChannelId $channelId,
        AttributeId $attributeId,
        AggregateId $optionId,
        string $shopwareId
    ): void;

    public function exists(ChannelId $channelId, AttributeId $attributeId, AggregateId $optionId): bool;
}
