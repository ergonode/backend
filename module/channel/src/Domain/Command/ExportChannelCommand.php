<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

class ExportChannelCommand implements ChannelCommandInterface
{
    private ExportId $exportId;

    private ChannelId $channelId;

    public function __construct(ExportId $exportId, ChannelId $channelId)
    {
        $this->exportId = $exportId;
        $this->channelId = $channelId;
    }

    public function getExportId(): ExportId
    {
        return $this->exportId;
    }

    public function getChannelId(): ChannelId
    {
        return $this->channelId;
    }
}
