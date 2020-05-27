<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ChannelCreatedEvent implements DomainEventInterface
{
    /**
     * @var ChannelId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ChannelId")
     */
    private ChannelId $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @var ExportProfileId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId")
     */
    private ExportProfileId $exportProfileId;

    /**
     * @param ChannelId       $channelId
     * @param string          $name
     * @param ExportProfileId $exportProfileId
     */
    public function __construct(
        ChannelId $channelId,
        string $name,
        ExportProfileId $exportProfileId
    ) {
        $this->id = $channelId;
        $this->name = $name;
        $this->exportProfileId = $exportProfileId;
    }

    /**
     * @return ChannelId
     */
    public function getAggregateId(): ChannelId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return ExportProfileId
     */
    public function getExportProfileId(): ExportProfileId
    {
        return $this->exportProfileId;
    }
}
