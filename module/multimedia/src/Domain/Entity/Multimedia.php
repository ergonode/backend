<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Entity;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Multimedia\Domain\Event\MultimediaCreatedEvent;

/**
 */
class Multimedia extends AbstractAggregateRoot
{
    /**
     * @var MultimediaId
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string;
     */
    private $extension;

    /**
     * @var string|null;
     */
    private $mime;

    /**
     * The file size in bytes.
     *
     * @var int
     */
    private $size;

    /**
     * The crc is hashed with crc32b hashing algorithm
     *
     * @var string;
     */
    private $crc;

    /**
     * @param MultimediaId $id
     * @param string       $name
     * @param string       $extension
     * @param int          $size      The file size in bytes.
     * @param string       $crc       The crc is hashed with crc32b hashing algorithm
     * @param string|null  $mime
     *
     * @throws \Exception
     */
    public function __construct(
        MultimediaId $id,
        string $name,
        string $extension,
        int $size,
        string $crc,
        ?string $mime = null
    ) {
        $this->apply(
            new MultimediaCreatedEvent(
                $id,
                $name,
                $extension,
                $size,
                $crc,
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
     * @return AbstractId
     */
    public function getId(): AbstractId
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
     * @return string
     */
    public function getCrc(): string
    {
        return $this->crc;
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
        $this->crc = $event->getCrc();
    }
}
