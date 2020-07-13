<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Entity;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;

/**
 */
class Workflow extends AbstractWorkflow
{
    public const DEFAULT = 'default';
    public const TYPE = 'default';


    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
