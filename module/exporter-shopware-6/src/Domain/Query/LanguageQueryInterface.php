<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

interface LanguageQueryInterface
{
    public function cleanData(ChannelId $channelId, \DateTimeImmutable $dateTime): void;
}
