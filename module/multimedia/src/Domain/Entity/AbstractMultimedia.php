<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Entity;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Multimedia\Domain\Event\MultimediaCreatedEvent;
use Ergonode\Multimedia\Domain\ValueObject\Hash;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Multimedia\Domain\Event\MultimediaAltChangedEvent;
use Ergonode\Multimedia\Domain\Event\MultimediaNameChangedEvent;

/**
 */
abstract class AbstractMultimedia extends AbstractAggregateRoot
{
    /**
     * @var MultimediaId
     */
    private MultimediaId $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $extension;

    /**
     * @var string|null
     */
    private ?string $mime;

    /**
     * The file size in bytes.
     *
     * @var int
     */
    private int $size;

    /**
     * @var Hash
     */
    private Hash $hash;

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
     * @return string
     */
    public function getFileName(): string
    {
        return sprintf('%s.%s', $this->hash->getValue(), $this->extension);
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
     * @param string $name
     *
     * @throws \Exception
     */
    public function changeName(string $name): void
    {
        if ($name !== $this->getName()) {
            $this->apply(new MultimediaNameChangedEvent($this->id, $name));
        }
    }

    /**
     * @return MultimediaId
     */
    public function getId(): MultimediaId
    {
        return $this->id;
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
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @return string|null
     */
    public function getMime(): ?string
    {
        return $this->mime;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return Hash
     */
    public function getHash(): Hash
    {
        return $this->hash;
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

    /**
     * @param MultimediaNameChangedEvent $event
     */
    protected function applyMultimediaNameChangedEvent(MultimediaNameChangedEvent $event): void
    {
        $this->name = $event->getName();
    }
}
