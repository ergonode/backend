<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Exception;

class NotImplementedException extends \Exception
{
    public function __construct(string $message = 'Feature not implemented')
    {
        parent::__construct($message);
    }
}
