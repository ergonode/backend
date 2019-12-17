<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;

class MultimediaCreatedEvent implements DomainEventInterface
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
     * @var int
     */
    private $size;

    /**
     * @var string;
     */
    private $crc;

    /**
     * @param MultimediaId $id
     * @param string $name
     * @param string $extension
     * @param string|null $mime
     * @param int $size
     * @param string $crc
     */
    public function __construct(MultimediaId $id, string $name, string $extension, ?string $mime, int $size, string $crc)
    {
        $this->id = $id;
        $this->name = $name;
        $this->extension = $extension;
        $this->mime = $mime;
        $this->size = $size;
        $this->crc = $crc;
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
     * @return string
     */
    public function getCrc(): string
    {
        return $this->crc;
    }
}
