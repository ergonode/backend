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

/**
 */
class Multimedia extends AbstractAggregateRoot
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
        return sprintf('%s.%s', $this->id, $this->extension);
    }

    /**
     * @return MultimediaId
     */
    public function getId(): MultimediaId
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
    }
}
