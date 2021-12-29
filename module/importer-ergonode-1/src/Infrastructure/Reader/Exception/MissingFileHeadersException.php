<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Reader\Exception;

class MissingFileHeadersException extends ReaderFileProcessException
{
    private array $missingHeaders;

    public function __construct(
        array $missingHeaders,
        string $filepath,
        string $filename = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct($filepath, $filename, $previous);
        $this->missingHeaders = $missingHeaders;
        $headers = implode(', ', $missingHeaders);
        $this->message = "$headers headers missing in {$this->getFilename()}";
    }

    public function getMissingHeaders(): array
    {
        return $this->missingHeaders;
    }
}
