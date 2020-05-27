<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Entity;

use Ergonode\Channel\Domain\Event\ChannelCreatedEvent;
use Ergonode\Channel\Domain\Event\ChannelNameChangedEvent;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;

/**
 */
class Channel extends AbstractAggregateRoot
{
    /**
     * @var ChannelId
     */
    private ChannelId $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var ExportProfileId
     */
    private ExportProfileId $exportProfileId;

    /**
     * @param ChannelId       $channelId
     * @param string          $name
     * @param ExportProfileId $exportProfileId
     *
     * @throws \Exception
     */
    public function __construct(ChannelId $channelId, string $name, ExportProfileId $exportProfileId)
    {
        $this->apply(new ChannelCreatedEvent($channelId, $name, $exportProfileId));
    }

    /**
     * @return ChannelId
     */
    public function getId(): ChannelId
    {
        return $this->id;
    }

    /**
     * @param string $name
     *
     * @throws \Exception
     */
    public function changeName(string $name): void
    {
        if (!$this->name !== $name) {
            $this->apply(new ChannelNameChangedEvent($this->id, $this->name, $name));
        }
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

    /**
     * @param ChannelCreatedEvent $event
     */
    public function applyChannelCreatedEvent(ChannelCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->name = $event->getName();
        $this->exportProfileId = $event->getExportProfileId();
    }

    /**
     * @param ChannelNameChangedEvent $event
     */
    public function applyChannelNameChangedEvent(ChannelNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }
}
