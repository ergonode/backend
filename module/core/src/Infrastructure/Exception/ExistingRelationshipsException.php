<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Exception;

use Ergonode\SharedKernel\Domain\AggregateId;

class ExistingRelationshipsException extends \Exception
{
    public function __construct(AggregateId $id)
    {
        $message = sprintf(
            'Element by ID "%s" has existing relationships',
            $id->getValue()
        );

        parent::__construct($message);
    }
}
