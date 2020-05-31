<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Service\Metadata;

/**
 */
class MetadataReader
{
    /**
     * @var MetadataReaderInterface[]
     */
    private array $readers;

    /**
     * @param MetadataReaderInterface ...$readers
     */
    public function __construct(MetadataReaderInterface ...$readers)
    {
        $this->readers = $readers;
    }

    /**
     * @param string $file
     *
     * @return array
     */
    public function read(string $file): array
    {
        $result = [];
        foreach ($this->readers as $reader) {
            $metadata = $reader->read($file);
            $result = array_merge($result, $metadata);
        }

        return $result;
    }
}
