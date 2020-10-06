<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Model;

/**
 */
final class MultimediaModel
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $filename;

    /**
     * @var string
     */
    private string $extension;

    /**
     * @var string
     */
    private string $mime;

    /**
     * @var int
     */
    private int $size;

    /**
     * @var array
     */
    private array $translations;

    /**
     * @param string $id
     * @param string $filename
     * @param string $extension
     * @param string $mime
     * @param int $size
     */
    public function __construct(
        string $id,
        string $filename,
        string $extension,
        string $mime,
        int $size
    ) {
        $this->id = $id;
        $this->filename = $filename;
        $this->extension = $extension;
        $this->mime = $mime;
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @return string
     */
    public function getMime(): string
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
     * @param string $language
     * @param string $name
     * @param string $alt
     */
    public function addTranslation(string $language, string $name, string $alt): void
    {
        $this->translations[$language] = [
            'name' => $name,
            'alt' => $alt,
        ];
    }

    /**
     * @return array
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }
}