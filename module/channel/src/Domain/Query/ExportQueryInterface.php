<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

interface ExportQueryInterface
{
    public function getProfileInfo(Language $language): array;

    public function getInformation(ExportId $exportId): array;

    public function findLastExport(ChannelId $channelId): ?\DateTime;

    public function getExportIdsByChannelId(ChannelId $channelId): array;

    public function getChannelTypeByExportId(ExportId $exportId): ?string;

    /**
     * @return ExportId[]
     */
    public function findActiveExport(ChannelId $channelId): array;
}
