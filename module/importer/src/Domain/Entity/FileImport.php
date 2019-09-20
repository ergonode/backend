<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Entity;

use Ergonode\Reader\Domain\Entity\ReaderId;

/**
 */
class FileImport extends AbstractImport
{
    public const TYPE = 'FILE';

    /**
     * @param ImportId $id
     * @param string   $name
     * @param ReaderId $readerId
     * @param string   $filename
     */
    public function __construct(ImportId $id, string $name, ReaderId $readerId, string $filename)
    {
        parent::__construct($id, $name);

        $this->options['file'] = $filename;
        $this->options['readerId'] = $readerId->getValue();
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
     * @return ReaderId
     */
    public function getReaderId(): ReaderId
    {
        return new ReaderId($this->options['readerId']);
    }
}
