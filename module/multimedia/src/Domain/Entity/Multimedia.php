<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Entity;

use Symfony\Component\HttpFoundation\File\File;

/**
 */
class Multimedia
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
     * @param MultimediaId  $id
     * @param string        $name
     * @param string        $extension
     * @param int           $size
     * @param string        $crc
     * @param string|string $mime
     */
    public function __construct(MultimediaId $id, string $name, string $extension, int $size, string $crc, ?string $mime = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->extension = $extension;
        $this->mime = $mime;
        $this->size = $size;
        $this->crc = $crc;
    }

    /**
     * @param MultimediaId $id
     * @param string       $name
     * @param File         $file
     * @param string       $crc
     *
     * @return Multimedia
     */
    public static function createFromFile(MultimediaId $id, string $name, File $file, string $crc): self
    {
        return new self($id, $name, $file->getExtension(), $file->getSize(), $crc, $file->getMimeType());
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
     * @return string
     */
    public function getCrc(): string
    {
        return $this->crc;
    }
}
