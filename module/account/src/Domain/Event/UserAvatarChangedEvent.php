<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class UserAvatarChangedEvent implements DomainEventInterface
{
    /**
     * @var MultimediaId|null
     *
     * @JMS\Type("Ergonode\Multimedia\Domain\Entity\MultimediaId")
     */
    private $avatarId;

    /**
     * @param MultimediaId|null $avatarId
     */
    public function __construct(MultimediaId $avatarId = null)
    {
        $this->avatarId = $avatarId;
    }
    /**
     * @return MultimediaId|null
     */
    public function getAvatarId(): ?MultimediaId
    {
        return $this->avatarId;
    }
}
