<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

interface CustomFieldQueryInterface
{
    public function loadByShopwareId(ChannelId $channel, string $shopwareId): ?AttributeId;

    public function cleanData(ChannelId $channel, \DateTimeImmutable $dateTime, string $type): void;
}
