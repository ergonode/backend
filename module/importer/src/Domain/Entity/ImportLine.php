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
     * @var ImportLineId
     */
    private ImportLineId $id;

    /**
     * @var ImportId
     */
    private ImportId $importId;

    /**
     * @var string
     */
    private string $content;

    /**
     * @param ImportLineId $id
     * @param ImportId     $importId
     * @param string       $content
     */
    public function __construct(ImportLineId $id, ImportId $importId, string $content)
    {
        $this->id = $id;
        $this->importId = $importId;
        $this->content = $content;
    }

    /**
     * @return ImportLineId
     */
    public function getId(): ImportLineId
    {
        return $this->id;
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
}
