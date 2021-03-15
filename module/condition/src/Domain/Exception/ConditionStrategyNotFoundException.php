<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Exception;

class ConditionStrategyNotFoundException extends \Exception
{
    public function __construct(string $type)
    {
        $message = sprintf('Can\'t find configuration strategy for "%s" condition type', $type);

        parent::__construct($message);
    }
}
