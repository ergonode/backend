<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Command;

use Ergonode\Channel\Domain\Entity\ChannelId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Segment\Domain\Entity\SegmentId;

/**
 */
class UpdateChannelCommand implements DomainCommandInterface
{
    /**
     * @var ChannelId
     *
     * @JMS\Type("Ergonode\Channel\Domain\Entity\ChannelId")
     */
    private ChannelId $id;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    /**
     * @var SegmentId
     *
     * @JMS\Type("Ergonode\Segment\Domain\Entity\SegmentId")
     */
    private SegmentId $segmentId;

    /**
     * @param ChannelId          $id
     * @param TranslatableString $name
     * @param SegmentId          $segmentId
     */
    public function __construct(ChannelId $id, TranslatableString $name, SegmentId $segmentId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->segmentId = $segmentId;
    }

    /**
     * @return ChannelId
     */
    public function getId(): ChannelId
    {
        return $this->id;
    }

    /**
     * @return TranslatableString
     */
    public function getName(): TranslatableString
    {
        return $this->name;
    }

    /**
     * @return SegmentId
     */
    public function getSegmentId(): SegmentId
    {
        return $this->segmentId;
    }
}
