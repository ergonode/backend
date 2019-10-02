<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Exception;

use Ergonode\Core\Domain\Entity\AbstractId;

/**
 */
class ExistingRelationshipsException extends \Exception
{
    /**
     * @param AbstractId $id
     */
    public function __construct(AbstractId $id)
    {
        $message = sprintf(
            'Element by ID "%s" has existing relationships',
            $id->getValue()
        );

        parent::__construct($message);
    }
}
