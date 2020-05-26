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
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
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
     * @var ExportProfileId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId")
     */
    private ExportProfileId $exportProfileId;

    /**
     * @param ExportId        $exportId
     * @param ChannelId       $channelId
     * @param ExportProfileId $exportProfileId
     */
    public function __construct(ExportId $exportId, ChannelId $channelId, ExportProfileId $exportProfileId)
    {
        $this->exportId = $exportId;
        $this->channelId = $channelId;
        $this->exportProfileId = $exportProfileId;
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

    /**
     * @return ExportProfileId
     */
    public function getExportProfileId(): ExportProfileId
    {
        return $this->exportProfileId;
    }
}
