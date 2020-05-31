<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Service\Metadata;

/**
 */
interface MetadataReaderInterface
{
    /**
     * @param string $file
     *
     * @return array
     */
    public function read(string $file): array;
}
