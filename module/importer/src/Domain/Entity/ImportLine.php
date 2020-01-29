<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Entity;

/**
 */
class ImportLine
{
    /**
     * @var int
     */
    private int $line;

    /**
     * @var ImportId
     */
    private ImportId $importId;

    /**
     * @var string
     */
    private string $content;

    /**
     * @var string|null
     */
    private ?string $error;

    /**
     * @param ImportId $importId
     * @param int      $line
     * @param string   $content
     */
    public function __construct(ImportId $importId, int $line, string $content)
    {
        $this->line = $line;
        $this->importId = $importId;
        $this->content = $content;
        $this->error = null;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * @return ImportId
     */
    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $error
     */
    public function addError(string $error): void
    {
        $this->error = $error;
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return null !== $this->error;
    }
}
