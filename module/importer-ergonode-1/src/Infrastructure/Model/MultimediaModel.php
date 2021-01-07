<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Model;

class MultimediaModel
{
    private string $id;
    private string $filename;
    private string $extension;
    private string $mime;
    private int $size;
    private array $translations;

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

    public function getId(): string
    {
        return $this->id;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getMime(): string
    {
        return $this->mime;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function addTranslation(string $language, string $name, string $alt): void
    {
        $this->translations[$language] = [
            'name' => $name,
            'alt' => $alt,
        ];
    }

    public function getTranslations(): array
    {
        return $this->translations;
    }
}
