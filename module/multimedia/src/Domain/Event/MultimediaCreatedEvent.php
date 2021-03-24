<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Domain\Event;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Multimedia\Domain\ValueObject\Hash;

class MultimediaCreatedEvent implements AggregateEventInterface
{
    private MultimediaId $id;

    private string $name;

    private string $extension;

    private ?string $mime;

    /**
     * The file size in bytes.
     */
    private int $size;

    private Hash $hash;

    /**
     * @param int $size The file size in bytes.
     */
    public function __construct(
        MultimediaId $id,
        string $name,
        string $extension,
        int $size,
        Hash $hash,
        ?string $mime = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->extension = $extension;
        $this->mime = $mime;
        $this->size = $size;
        $this->hash = $hash;
    }

    public function getAggregateId(): MultimediaId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getMime(): ?string
    {
        return $this->mime;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getHash(): Hash
    {
        return $this->hash;
    }
}
