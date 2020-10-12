<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Reader;

use Ergonode\ImporterErgonode\Infrastructure\Reader\Exception\ReaderFileProcessException;
use Iterator;
use League\Csv\Reader;

/**
 */
abstract class AbstractErgonodeReader
{
    /**
     * @var array
     */
    protected array $headers;

    /**
     * @var Iterator
     */
    protected Iterator $records;

    /**
     * @param string $directory
     * @param string $file
     *
     * @throws ReaderFileProcessException
     */
    public function __construct(string $directory, string $file)
    {
        try {
            $reader = Reader::createFromPath("$directory/$file");
            $reader->setHeaderOffset(0);
            $reader->skipEmptyRecords();
            $reader->skipInputBOM();
            $this->headers = $reader->getHeader();
            $this->records = $reader->getRecords();
            $this->records->rewind();
        } catch (\Exception $exception) {
            throw new ReaderFileProcessException($directory, $exception);
        }
    }
}
