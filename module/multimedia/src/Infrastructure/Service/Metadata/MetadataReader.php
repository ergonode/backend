<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Service\Metadata;

class MetadataReader
{
    /**
     * @var MetadataReaderInterface[]
     */
    private array $readers;

    public function __construct(MetadataReaderInterface ...$readers)
    {
        $this->readers = $readers;
    }

    /**
     * @param resource $file
     *
     * @return array
     */
    public function read($file): array
    {
        $result = [];
        foreach ($this->readers as $reader) {
            $metadata = $reader->read($file);
            $result = array_merge($result, $metadata);
        }

        return $result;
    }
}
