<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Reader;

use Ergonode\ImporterErgonode1\Infrastructure\Reader\Exception\ReaderFileProcessException;
use Iterator;
use League\Csv\Reader;

abstract class AbstractErgonodeReader
{
    protected array $headers;
    protected Iterator $records;

    /**
     * @throws ReaderFileProcessException
     */
    public function __construct(string $directory, string $file)
    {
        $filepath = sprintf('%s%s%s', $directory, DIRECTORY_SEPARATOR, $file);

        try {
            $reader = Reader::createFromPath($filepath);
            $reader->setHeaderOffset(0);
            $reader->skipEmptyRecords();
            $reader->skipInputBOM();
            $this->headers = $reader->getHeader();
            $this->records = $reader->getRecords();
            $this->records->rewind();
        } catch (\Exception $exception) {
            throw new ReaderFileProcessException($filepath, $file);
        }
    }
}
