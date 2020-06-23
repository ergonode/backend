<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Entity;

use Ergonode\Multimedia\Domain\Event\AvatarCreatedEvent;
use Ergonode\Multimedia\Domain\ValueObject\Hash;
use Ergonode\SharedKernel\Domain\Aggregate\AvatarId;

/**
 */
class Avatar extends AbstractResource
{
    /**
     * @var AvatarId
     */
    private AvatarId $id;

    /**
     * @param AvatarId    $id
     * @param string      $extension
     * @param int         $size
     * @param Hash        $hash
     * @param string|null $mime
     *
     * @throws \Exception
     */
    public function __construct(
        AvatarId $id,
        string $extension,
        int $size,
        Hash $hash,
        ?string $mime = null
    ) {
        $this->apply(
            new AvatarCreatedEvent(
                $id,
                $extension,
                $size,
                $hash,
                $mime,
            )
        );
    }

    /**
     * @return AvatarId
     */
    public function getId(): AvatarId
    {
        return $this->id;
    }

    /**
     * @param AvatarCreatedEvent $event
     */
    protected function applyAvatarCreatedEvent(AvatarCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->extension = $event->getExtension();
        $this->mime = $event->getMime();
        $this->size = $event->getSize();
        $this->hash = $event->getHash();
    }
}
