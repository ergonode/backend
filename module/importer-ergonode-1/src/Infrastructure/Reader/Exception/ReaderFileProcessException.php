<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Reader\Exception;

use Exception;
use Throwable;

class ReaderFileProcessException extends Exception
{
    private string $filename;

    public function __construct(string $filepath, string $filename = null, ?Throwable $previous = null)
    {
        if ($filename) {
            $this->filename = $filename;
        }
        parent::__construct("Can't process \"$filepath\" file", 1, $previous);
    }

    public function getFilename(): string
    {
        return $this->filename;
    }
}
