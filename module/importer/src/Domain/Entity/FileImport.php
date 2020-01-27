<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Entity;

/**
 */
class FileImport extends AbstractImport
{
    public const TYPE = 'FILE';

    /**
     * @param ImportId $id
     * @param string   $name
     * @param string   $filename
     * @param string   $sourceType
     */
    public function __construct(ImportId $id, string $name, string $filename, string $sourceType)
    {
        parent::__construct($id, $name);

        $this->options['file'] = $filename;
        $this->options['source_type'] = $sourceType;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->options['file'];
    }

    /**
     * @return string
     */
    public function getSourceType(): string
    {
        return $this->options['source_type'];
    }
}
