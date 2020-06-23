<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Multimedia\Domain\Event\MultimediaAltChangedEvent;
use Ergonode\Multimedia\Domain\Event\MultimediaCreatedEvent;
use Ergonode\Multimedia\Domain\ValueObject\Hash;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

/**
 */
class Multimedia extends AbstractResource
{
    /**
     * @var MultimediaId
     */
    protected MultimediaId $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var TranslatableString
     */
    private TranslatableString $alt;

    /**
     * @param MultimediaId $id
     * @param string       $name
     * @param string       $extension
     * @param int          $size      The file size in bytes.
     * @param Hash         $hash
     * @param string|null  $mime
     *
     * @throws \Exception
     */
    public function __construct(
        MultimediaId $id,
        string $name,
        string $extension,
        int $size,
        Hash $hash,
        ?string $mime = null
    ) {
        $this->apply(
            new MultimediaCreatedEvent(
                $id,
                $name,
                $extension,
                $size,
                $hash,
                $mime
            )
        );
    }

    /**
     * @return MultimediaId
     */
    public function getId(): MultimediaId
    {
        return $this->id;
    }

    /**
     * @param TranslatableString $alt
     *
     * @throws \Exception
     */
    public function changeAlt(TranslatableString $alt): void
    {
        if (!$alt->isEqual($this->alt)) {
            $this->apply(new MultimediaAltChangedEvent($this->id, $alt));
        }
    }

    /**
     * @return TranslatableString
     */
    public function getAlt(): TranslatableString
    {
        return $this->alt;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param MultimediaCreatedEvent $event
     */
    protected function applyMultimediaCreatedEvent(MultimediaCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->name = $event->getName();
        $this->extension = $event->getExtension();
        $this->mime = $event->getMime();
        $this->size = $event->getSize();
        $this->hash = $event->getHash();
        $this->alt = new TranslatableString();
    }

    /**
     * @param MultimediaAltChangedEvent $event
     */
    protected function applyMultimediaAltChangedEvent(MultimediaAltChangedEvent $event): void
    {
        $this->alt = $event->getAlt();
    }
}
