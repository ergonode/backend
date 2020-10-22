<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Entity;

class Workflow extends AbstractWorkflow
{
    public const DEFAULT = 'default';
    public const TYPE = 'default';


    public static function getType(): string
    {
        return self::TYPE;
    }
}
