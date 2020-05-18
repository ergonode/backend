<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Writer;

/**
 */
interface WriterInterface
{
    /**
     * @param string $file
     * @param array  $configuration
     */
    public function open(string $file, array $configuration = []): void;

    /**
     * @param array $data
     */
    public function onStart(array $data): void;

    /**
     * @param array $data
     */
    public function write(array $data): void;
}