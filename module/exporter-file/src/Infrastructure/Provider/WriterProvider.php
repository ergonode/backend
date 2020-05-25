<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Provider;

use Ergonode\ExporterFile\Infrastructure\Writer\WriterInterface;

/**
 */
class WriterProvider
{
    /**
     * @var WriterInterface[]
     */
    private array $writers;

    /**
     * @param WriterInterface ...$writers
     */
    public function __construct(WriterInterface ...$writers)
    {
        $this->writers = $writers;
    }

    /**
     * @param string $type
     *
     * @return WriterInterface
     */
    public function provide(string $type): WriterInterface
    {
        foreach ($this->writers as $writer) {
            if ($writer->support($type)) {
                return $writer;
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find writer type %s', $type));
    }
}
