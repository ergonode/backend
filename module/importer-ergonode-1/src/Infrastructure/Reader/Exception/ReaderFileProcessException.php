<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Reader\Exception;

use Exception;
use Throwable;

class ReaderFileProcessException extends Exception
{
    private string $filename;
    private string $filepath;

    public function __construct(
        string $filepath,
        string $filename = null,
        ?Throwable $previous = null,
        ?string $detailsMessage = null
    ) {
        if ($filename) {
            $this->filename = $filename;
        }
        $this->filepath = $filepath;
        $filename = $filename ?? $filepath;
        parent::__construct("Can't process \"$filename\" file.$detailsMessage", 1, $previous);
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getFilepath(): string
    {
        return $this->filepath;
    }
}
