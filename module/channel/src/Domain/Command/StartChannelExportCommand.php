<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class StartChannelExportCommand implements DomainCommandInterface
{
    /**
     * @var ExportId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ExportId")
     */
    private ExportId $exportId;

    /**
     * @var ChannelId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ChannelId")
     */
    private ChannelId $channelId;

    /**
     * @param ExportId  $exportId
     * @param ChannelId $channelId
     */
    public function __construct(ExportId $exportId, ChannelId $channelId)
    {
        $this->exportId = $exportId;
        $this->channelId = $channelId;
    }

    /**
     * @return ExportId
     */
    public function getExportId(): ExportId
    {
        return $this->exportId;
    }

    /**
     * @return ChannelId
     */
    public function getChannelId(): ChannelId
    {
        return $this->channelId;
    }
}
