<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

/**
 */
final class ImportMultimediaCommand implements DomainCommandInterface
{
    /**
     * @var ImportId
     */
    private ImportId $importId;

    /**
     * @var MultimediaId
     */
    private MultimediaId $id;

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
     * @var TranslatableString
     */
    private TranslatableString $translations;

    /**
     * @param ImportId           $importId
     * @param MultimediaId       $id
     * @param string             $filename
     * @param string             $extension
     * @param string             $mime
     * @param int                $size
     * @param TranslatableString $translations
     */
    public function __construct(
        ImportId $importId,
        MultimediaId $id,
        string $filename,
        string $extension,
        string $mime,
        int $size,
        TranslatableString $translations
    ) {
        $this->importId = $importId;
        $this->id = $id;
        $this->filename = $filename;
        $this->extension = $extension;
        $this->mime = $mime;
        $this->size = $size;
        $this->translations = $translations;
    }

    /**
     * @return ImportId
     */
    public function getImportId(): ImportId
    {
        return $this->importId;
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
     * @return TranslatableString
     */
    public function getTranslations(): TranslatableString
    {
        return $this->translations;
    }
}