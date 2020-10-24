<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Reader\Exception;

use Exception;
use Throwable;

final class ReaderFileProcessException extends Exception
{
    public function __construct(string $file, ?Throwable $previous = null)
    {
        parent::__construct("Can't process \"$file\" file", 1, $previous);
    }
}
