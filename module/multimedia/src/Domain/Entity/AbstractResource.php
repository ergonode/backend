<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Entity;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Multimedia\Domain\ValueObject\Hash;

/**
 */
abstract class AbstractResource extends AbstractAggregateRoot
{

    /**
     * @var string
     */
    protected string $extension;

    /**
     * @var string|null
     */
    protected ?string $mime;

    /**
     * The file size in bytes.
     *
     * @var int
     */
    protected int $size;

    /**
     * @var Hash
     */
    protected Hash $hash;

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return sprintf('%s.%s', $this->hash->getValue(), $this->extension);
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
