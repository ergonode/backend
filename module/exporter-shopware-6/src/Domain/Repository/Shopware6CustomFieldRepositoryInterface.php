<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

interface Shopware6CustomFieldRepositoryInterface
{
    public function load(ChannelId $channel, AttributeId $attributeId): ?string;

    public function save(ChannelId $channel, AttributeId $attributeId, string $shopwareId, string $type): void;

    public function exists(ChannelId $channel, AttributeId $attributeId): bool;
}
