<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

interface PropertyGroupQueryInterface
{
    public function loadByShopwareId(ChannelId $channelId, string $shopwareId): ?AttributeId;

    public function cleanData(ChannelId $channelId, \DateTimeImmutable $dateTime, string $type): void;
}
