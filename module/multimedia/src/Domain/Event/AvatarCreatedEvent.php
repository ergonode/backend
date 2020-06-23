<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Multimedia\Domain\ValueObject\Hash;
use Ergonode\SharedKernel\Domain\Aggregate\AvatarId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AvatarCreatedEvent implements DomainEventInterface
{
    /**
     * @var AvatarId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AvatarId")
     */
    private AvatarId $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $extension;

    /**
     * @var string|null
     *
     * @JMS\Type("string")
     */
    private ?string $mime;

    /**
     * The file size in bytes.
     *
     * @var int
     *
     * @JMS\Type("int")
     */
    private int $size;

    /**
     * @var Hash
     *
     * @JMS\Type("Ergonode\Multimedia\Domain\ValueObject\Hash")
     */
    private Hash $hash;

    /**
     * @param AvatarId    $id
     * @param string      $extension
     * @param int         $size
     * @param Hash        $hash
     * @param string|null $mime
     */
    public function __construct(
        AvatarId $id,
        string $extension,
        int $size,
        Hash $hash,
        ?string $mime = null
    ) {
        $this->id = $id;
        $this->extension = $extension;
        $this->mime = $mime;
        $this->size = $size;
        $this->hash = $hash;
    }

    /**
     * @return AvatarId
     */
    public function getAggregateId(): AvatarId
    {
        return $this->id;
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
}
