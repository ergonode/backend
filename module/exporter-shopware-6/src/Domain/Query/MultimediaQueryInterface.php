<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

interface MultimediaQueryInterface
{
    public function loadByShopwareId(ChannelId $channel, string $shopwareId): ?MultimediaId;

    public function cleanData(ChannelId $channel, \DateTimeImmutable $dateTime): void;
}
